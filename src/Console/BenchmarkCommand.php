<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use React\EventLoop\Loop;
use ThePHPBench\Configuration;
use ThePHPBench\Configurations;
use ThePHPBench\CSV\StreamWriter;
use ThePHPBench\MachineInfo;
use ThePHPBench\RuntimeRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Symfony Console command that orchestrates the full benchmark suite.
 *
 * Each test configuration is run in an isolated subprocess via ReactPHP
 * ChildProcess so that:
 *   - Memory and timing are clean per test (no cross-ORM contamination)
 *   - The parent process can measure true wall-clock time per worker
 *   - Stdout/stderr lifecycle is fully managed
 *   - Tests run sequentially by default (one at a time) to avoid DB conflicts,
 *     with an optional --concurrency flag for parallel runs on separate DBs
 *
 * Usage:
 *   ./bin/the-php-bench benchmark
 *   ./bin/the-php-bench benchmark DivergenceV2
 *   ./bin/the-php-bench benchmark DivergenceV2.MariaDB
 *   ./bin/the-php-bench benchmark --runs=10
 *   ./bin/the-php-bench benchmark --concurrency=4
 *   ./bin/the-php-bench benchmark --config=config/my-config.php
 */
#[AsCommand(name: 'benchmark', description: 'Run PHP ORM benchmarks in isolated subprocesses')]
class BenchmarkCommand extends Command
	{
	private const RESULTS_SCHEMA_VERSION = 4;
	private const FOOTER_SENTINEL = '# THEPHPBENCH-STATUS';

	protected function configure() : void
		{
		$this
			->addArgument('filter', InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
				'Filter tests by namespace[.description] — e.g. DivergenceV2 or DivergenceV2.MariaDB')
			->addOption('config', 'c', InputOption::VALUE_REQUIRED,
				'Path to benchmark config', 'config/config.php')
			->addOption('batch-sizes', null, InputOption::VALUE_REQUIRED,
				'Comma-separated iteration counts to run, e.g. 10,50,100')
			->addOption('no-phar', null, InputOption::VALUE_NONE,
				'Run each runtime worker directly instead of using built runtime PHARs')
			->addOption('results', 'r', InputOption::VALUE_REQUIRED,
				'Path to single-run results CSV file (default: results/results-$runID-$testLabel-$timestamp.csv)')
			->addOption('type', null, InputOption::VALUE_REQUIRED,
				'Benchmark run type label (defaults to config metadata or standard-full)')
			->addOption('label', null, InputOption::VALUE_REQUIRED,
				'Benchmark run test label (defaults to config metadata or config basename)')
			->addOption('run-id', null, InputOption::VALUE_REQUIRED,
				'Benchmark run ID (defaults to an auto-generated id)')
			->addOption('runs', null, InputOption::VALUE_REQUIRED,
				'How many repeated runs to execute per benchmark (run 1 is labeled warmup)', '10')
			->addOption('concurrency', 'j', InputOption::VALUE_REQUIRED,
				'Max parallel workers (default 1 = sequential)', '1');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$runStartedAt = date('c');
		$runStartedAtMonotonic = microtime(true);
		$configFile  = (string)$input->getOption('config');
		$batchSizesOption = $input->getOption('batch-sizes');
		$useDirectWorkers = (bool)$input->getOption('no-phar');
		$runs        = max(1, (int)$input->getOption('runs'));
		$concurrency = max(1, (int)$input->getOption('concurrency'));
		$filters     = (array)$input->getArgument('filter');
		$batchSizes = $this->parseBatchSizes(\is_string($batchSizesOption) ? $batchSizesOption : null);

		if ($batchSizesOption !== null && $batchSizes === [])
			{
			$output->writeln('<error>Invalid --batch-sizes value. Use a comma-separated list of positive integers, e.g. 10,50,100.</error>');

			return Command::FAILURE;
			}

		$configs = new Configurations(realpath($configFile) ?: $configFile);
		$runType = (string)($input->getOption('type') ?: $configs->getRunType() ?: 'standard-full');
		$runLabel = (string)($input->getOption('label') ?: $configs->getRunLabel() ?: \pathinfo($configFile, \PATHINFO_FILENAME));
		$runId = (string)($input->getOption('run-id') ?: $this->generateRunId());
		$runTimestamp = date('Ymd-His');
		$resultsFile = (string)($input->getOption('results') ?: $this->defaultResultsFile($runId, $runLabel, $runTimestamp));
		$runMetadata = [
			'benchmark_run_id' => $runId,
			'benchmark_type' => $runType,
			'benchmark_label' => $runLabel,
			'benchmark_timestamp' => $runTimestamp,
		];
		$runManifest = $runMetadata + MachineInfo::all() + [
			'config_file' => $configFile,
			'configured_runs' => $runs,
			'concurrency' => $concurrency,
			'run_started_at' => $runStartedAt,
			'runner_pid' => \getmypid() ?: 0,
			'runner_status_check' => \sprintf('ps -p %d -o pid=,stat=,etime=,args=', \getmypid() ?: 0),
		];
		$queue = $this->buildQueue($configs, $filters, $runs, $batchSizes);

		if ($queue === [])
			{
			$output->writeln('<comment>No matching tests found.</comment>');

			return Command::SUCCESS;
			}

		if (! $useDirectWorkers)
			{
			$runtimeWarnings = [];
			$queue = $this->filterQueueForBuiltRuntimes($queue, $runtimeWarnings);

			foreach ($runtimeWarnings as $warning)
				{
				$output->writeln('<fg=yellow>⚠ ' . $warning . '</>');
				}

			if ($queue === [])
				{
				$output->writeln('<comment>No benchmarkable tests found after skipping frameworks with missing runtime PHARs.</comment>');

				return Command::SUCCESS;
				}
			}

		$queuedRuns = \count($queue);

		$output->writeln('');
		$output->writeln('<options=bold> PHP ORM Benchmark</>');
		$output->writeln('<fg=gray> ' . count($queue) . ' run(s) queued  ·  ' . $runs . ' run(s) per benchmark  ·  concurrency ' . $concurrency . '</>');
		$output->writeln('<fg=gray> run id ' . $runId . '  ·  type ' . $runType . '  ·  label ' . $runLabel . '</>');
		$output->writeln('<fg=gray> output ' . $resultsFile . '</>');
		$output->writeln('');

		$loop        = Loop::get();
		$newResults  = [];
		$pending     = $queue;
		$activeCount = 0;
		$hadFailures = false;

		$spawnNext = null;
		$spawnNext = function() use (
			&$pending, &$activeCount, &$hadFailures, &$newResults, &$spawnNext,
			$loop, $output, $concurrency, $queuedRuns, $resultsFile, $runMetadata, $runManifest, $runStartedAtMonotonic, $useDirectWorkers
		) : void {
			while ($activeCount < $concurrency && $pending !== [])
				{
				$item   = array_shift($pending);
				$index  = $item['index'];
				$queuePosition = $item['queuePosition'];
				$config = $item['config'];
				$runNumber = $item['runNumber'];
				$totalRuns = $item['totalRuns'];
				$workerBin = $useDirectWorkers
					? RuntimeRegistry::directWorkerBinFor($config)
					: RuntimeRegistry::workerBinFor($config);
				++$activeCount;

				$worker = new WorkerProcess($loop, $output, $workerBin, $config, $runMetadata, $queuePosition - 1, $queuedRuns, $runNumber, $totalRuns);
				$worker->spawn(function(?array $result, bool $failed) use (
					&$activeCount, &$hadFailures, &$newResults, &$pending, &$spawnNext, $loop, $resultsFile, $output, $runManifest, $queuedRuns, $runStartedAtMonotonic
				) : void {
					--$activeCount;

					if ($failed)
						{
						$hadFailures = true;
						}

					if ($result !== null)
						{
						$newResults[] = $result;
						$this->writeResults($resultsFile, $newResults, $runManifest + [
							'queued_runs' => $queuedRuns,
						], $output, [
							'status' => 'running',
							'completed' => false,
							'exit_code' => null,
							'elapsed_seconds' => \round(\max(0, microtime(true) - $runStartedAtMonotonic), 3),
						]);
						}

					if ($pending !== [] || $activeCount > 0)
						{
						$spawnNext();
						}
					else
						{
						$loop->stop();
						}
					});
				}
			};

		$spawnNext();
		$loop->run();

		if ($hadFailures)
			{
			$this->writeResults($resultsFile, $newResults, $runManifest + [
				'queued_runs' => $queuedRuns,
			], $output, [
				'status' => 'failed',
				'completed' => false,
				'exit_code' => Command::FAILURE,
				'elapsed_seconds' => \round(\max(0, microtime(true) - $runStartedAtMonotonic), 3),
			]);

			$output->writeln('');
			$output->writeln('<error>Benchmark failed. Partial results remain in ' . $resultsFile . ' for the completed benchmarks.</error>');
			$output->writeln('');

			return Command::FAILURE;
			}

		$this->writeResults($resultsFile, $newResults, $runManifest + [
			'queued_runs' => $queuedRuns,
		], $output, [
			'status' => 'complete',
			'completed' => true,
			'exit_code' => Command::SUCCESS,
			'elapsed_seconds' => \round(\max(0, microtime(true) - $runStartedAtMonotonic), 3),
		]);

		$output->writeln('');
		$output->writeln('<fg=green>✓ Benchmark complete. Results written to ' . $resultsFile . '</>');
		$output->writeln('');

		return Command::SUCCESS;
		}

	/**
	 * @param  list<array{index: int, queuePosition: int, config: Configuration, runNumber: int, totalRuns: int}> $queue
	 * @param  list<string> $warnings
	 * @return list<array{index: int, queuePosition: int, config: Configuration, runNumber: int, totalRuns: int}>
	 */
	private function filterQueueForBuiltRuntimes(array $queue, array &$warnings) : array
		{
		$availableQueue = [];
		$missingNamespaces = [];

		foreach ($queue as $item)
			{
			$config = $item['config'];
			$namespace = $config->getNamespace();

			if (isset($missingNamespaces[$namespace]))
				{
				continue;
				}

			if (! RuntimeRegistry::hasRuntimePhar($config))
				{
				$missingNamespaces[$namespace] = true;
				$warnings[] = RuntimeRegistry::missingRuntimeWarning($config);

				continue;
				}

			$availableQueue[] = $item;
			}

		foreach ($availableQueue as $queueIndex => &$item)
			{
			$item['queuePosition'] = $queueIndex + 1;
			}
		unset($item);

		return $availableQueue;
		}

	/**
	 * @param  list<string> $filters
	 * @return list<array{index: int, queuePosition: int, config: Configuration, runNumber: int, totalRuns: int}>
	 */
	private function buildQueue(Configurations $configs, array $filters, int $runs, array $batchSizes = []) : array
		{
		$queue = [];
		$index = -1;

		foreach ($configs as $config)
			{
			++$index;

			if (($filters === [] || $this->matchesFilter($config, $filters))
				&& ($batchSizes === [] || $this->matchesBatchSizes($config, $batchSizes)))
				{
				for ($runNumber = 1; $runNumber <= $runs; ++$runNumber)
					{
					$queue[] = [
						'index' => $index,
						'config' => $config,
						'priority' => $config->getPriority(),
						'runNumber' => $runNumber,
						'totalRuns' => $runs,
					];
					}
				}
			}

		usort($queue, static function(array $a, array $b) : int {
			$priorityComparison = $a['priority'] <=> $b['priority'];

			return 0 !== $priorityComparison ? $priorityComparison : ($a['index'] <=> $b['index']);
		});

		foreach ($queue as $queueIndex => &$item)
			{
			$item['queuePosition'] = $queueIndex + 1;
			unset($item['priority']);
			}
		unset($item);

		return $queue;
		}

	/**
	 * @return list<int>
	 */
	private function parseBatchSizes(?string $batchSizesOption) : array
		{
		if ($batchSizesOption === null)
			{
			return [];
			}

		$values = [];

		foreach (\explode(',', $batchSizesOption) as $part)
			{
			$part = \trim($part);

			if ($part === '' || ! \ctype_digit($part))
				{
				return [];
				}

			$value = (int)$part;

			if ($value < 1)
				{
				return [];
				}

			$values[$value] = $value;
			}

		return \array_values($values);
		}

	/**
	 * @param list<int> $batchSizes
	 */
	private function matchesBatchSizes(Configuration $config, array $batchSizes) : bool
		{
		return \in_array($config->getIterations(), $batchSizes, true);
		}

	/**
	 * @param list<string> $filters
	 */
	private function matchesFilter(Configuration $config, array $filters) : bool
		{
		$scenario = $config->getScenario();
		$haystack = \strtolower(\implode(' ', \array_filter([
			$config->getNamespace(),
			$config->getDescription(),
			$config->getDriver(),
			$scenario->id,
			$scenario->modelFamily,
			(string)$scenario->rowCount,
			$scenario->modelFamily . ' ' . $scenario->rowCount,
		], static fn(string $value) : bool => $value !== '')));

		foreach ($filters as $filter)
			{
			$filter = \strtolower(\trim($filter));

			if ($filter === '')
				{
				continue;
				}

			if (str_contains($filter, '.'))
				{
				[$nsPart, $descPart] = explode('.', $filter, 2);

				if (\strtolower($config->getNamespace()) === $nsPart && \strtolower($config->getDescription()) === $descPart)
					{
					continue;
					}
				}

			if (\str_contains($haystack, $filter))
				{
				continue;
				}

			return false;
			}

		return true;
		}

	/**
	 * @return list<array<string,mixed>>
	 */
	private function writeResults(string $file, array $newRows, array $manifest, OutputInterface $output, array $footer = []) : bool
		{
		try
			{
			$directory = \dirname($file);

			if ($directory !== '.' && ! \is_dir($directory) && ! \mkdir($directory, 0777, true) && ! \is_dir($directory))
				{
				throw new \RuntimeException("Unable to create results directory {$directory}");
				}

			$stream = \fopen($file, 'w');

			if (! \is_resource($stream))
				{
				throw new \RuntimeException("Unable to open {$file} for writing");
				}

			$columns = $this->resultColumns($newRows);
			$manifest = $manifest + [
				'format' => 'thephpbench-results-v2',
				'results_schema_version' => self::RESULTS_SCHEMA_VERSION,
				'column_count' => \count($columns),
				'columns' => $columns,
				'rows_written' => \count($newRows),
				'generated_at' => date('c'),
			];

			foreach ($this->manifestLines($manifest) as $line)
				{
				\fwrite($stream, $line . "\n");
				}

			$writer = new StreamWriter($stream, ',', '"', '\\', "\n");
			$writer->setRowColumns($columns);

			foreach ($newRows as $row)
				{
				$writer->outputRow($row);
				}

			foreach ($this->footerLines($footer + [
				'queued_runs' => (int)($manifest['queued_runs'] ?? 0),
				'rows_written' => \count($newRows),
				'updated_at' => date('c'),
			]) as $line)
				{
				\fwrite($stream, $line . "\n");
				}

			\fclose($stream);

			return true;
			}
		catch (\Throwable $e)
			{
			$output->writeln('<error>Failed to write results: ' . $e->getMessage() . '</error>');

			return false;
			}
		}

	/**
	 * @param list<array<string,mixed>> $rows
	 * @return list<string>
	 */
	private function resultColumns(array $rows) : array
		{
		$columns = [];

		foreach ($rows as $row)
			{
			foreach (\array_keys($row) as $column)
				{
				if (! \in_array($column, $columns, true))
					{
					$columns[] = (string)$column;
					}
				}
			}

		return $columns;
		}

	/**
	 * @param array<string,mixed> $manifest
	 * @return list<string>
	 */
	private function manifestLines(array $manifest) : array
		{
		return [
			'# THEPHPBENCH-MANIFEST',
			'# ' . \json_encode($manifest, JSON_UNESCAPED_SLASHES),
		];
		}

	/**
	 * @param array<string,mixed> $footer
	 * @return list<string>
	 */
	private function footerLines(array $footer) : array
		{
		return [
			self::FOOTER_SENTINEL,
			'# ' . \json_encode($footer, JSON_UNESCAPED_SLASHES),
		];
		}

	private function defaultResultsFile(string $runId, string $runLabel, string $runTimestamp) : string
		{
		return sprintf(
			'results/results-%s-%s-%s.csv',
			$this->slug($runId),
			$this->slug($runLabel),
			$this->slug($runTimestamp)
		);
		}

	private function generateRunId() : string
		{
		return date('YmdHis') . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
		}

	private function slug(string $value) : string
		{
		$value = strtolower(trim($value));
		$value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? 'run';

		return trim($value, '-') ?: 'run';
		}
	}
