<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use ThePHPBench\BackendLabel;
use ThePHPBench\FrameworkVersion;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'results', description: 'Render benchmark results with warmup-aware aggregates')]
class ResultsCommand extends Command
	{
	private const TIME_COLUMNS = [
		'Init Time' => 'Init',
		'Insert Time' => 'Insert',
		'Read Time' => 'Read',
		'Update Time' => 'Update',
		'Random Read Time' => 'Rand',
		'Delete Time' => 'Delete',
		'Total Runtime Time' => 'Total',
	];

	private const RUNTIME_PALETTE = [
		81, 214, 119, 205, 45, 190, 39, 177, 111, 221,
		49, 196, 159, 208, 121, 201, 75, 226, 43, 198,
		117, 220, 84, 203, 51, 191, 69, 227, 123, 199,
		87, 215, 154, 202, 48, 186, 44, 213, 118, 197,
	];

	/** @var list<array<string, string>> */
	private array $rawRows = [];

	/** @var list<array<string, mixed>> */
	private array $rows = [];

	/** @var array<string, int> */
	private array $palette = [];

	private int $labelWidth = 0;

	private string $file = '';

	private string $sourceSummary = '';

	/** @var array<string, mixed> */
	private array $manifest = [];

	/** @var array<string, mixed> */
	private array $footer = [];

	private OutputInterface $output;

	protected function configure() : void
		{
		$this
			->addArgument('file', InputArgument::OPTIONAL, 'Path to one results CSV file or a run id')
			->addOption('cumulative', null, InputOption::VALUE_NONE, 'Render the cumulative standard-full view instead of the latest run file')
			->addArgument('filter', InputArgument::OPTIONAL, 'Filter by ORM name prefix');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$this->output = $output;

		try
			{
			$this->file = (string)($input->getArgument('file') ?? '');
			$filter = $input->getArgument('filter') !== null ? (string)$input->getArgument('filter') : null;
			$cumulative = (bool)$input->getOption('cumulative');

			$this->load($this->file !== '' ? $this->file : null, $cumulative);
			$this->filter($filter);
			$this->aggregate();
			$this->render();
			}
		catch (\RuntimeException $e)
			{
			$output->writeln('<error>' . $e->getMessage() . '</error>');

			return Command::FAILURE;
			}

		return Command::SUCCESS;
		}

	private function load(?string $file, bool $cumulative) : void
		{
		if ($file !== null)
			{
			$resolved = $this->resolveRequestedFile($file);

			if ($resolved === null)
				{
				throw new \RuntimeException("Results file or run id not found: {$file}");
				}

			$this->loadFile($resolved);
			$this->manifest = $this->readManifest($resolved);
			$this->footer = $this->readFooter($resolved);
			$this->sourceSummary = $resolved;
			}
		else
			{
			$files = $this->discoverRunFiles();

			if ($files === [])
				{
				throw new \RuntimeException('No results CSV files found');
				}

			if ($cumulative)
				{
				$selection = $this->selectCumulativeFiles($files);
				$selected = $selection['files'];

				foreach ($selected as $selectedFile)
					{
					$this->loadFile($selectedFile);
					}

				$this->manifest = [];
				$this->sourceSummary = 'standard-full' === $selection['mode']
					? 'cumulative standard-full view from ' . \count($selected) . ' run file(s)'
					: 'latest run file ' . $selected[0];
				}
			else
				{
				$latest = $this->latestRunFile($files);
				$this->loadFile($latest);
				$this->manifest = $this->readManifest($latest);
				$this->footer = $this->readFooter($latest);
				$this->sourceSummary = 'latest run file ' . $latest;
				}
			}
		}

	private function loadFile(string $file) : void
		{
		foreach (new \ThePHPBench\CSV\FileReader($file) as $row)
			{
			$this->rawRows[] = $row;
			}

		if ($this->rawRows === [])
			{
			throw new \RuntimeException("No data in {$file}");
			}
		}

	private function filter(?string $prefix) : void
		{
		if ($prefix === null)
			{
			return;
			}

		$prefix = \strtolower($prefix);
		$this->rawRows = \array_values(\array_filter(
			$this->rawRows,
			static function(array $row) use ($prefix) : bool
				{
				$test = \strtolower((string)($row['Test'] ?? ''));
				$namespace = \strtolower((string)($row['Namespace'] ?? ''));

				return \str_starts_with($test, $prefix) || \str_starts_with($namespace, $prefix);
				}
		));

		if ($this->rawRows === [])
			{
			throw new \RuntimeException("No results matching '{$prefix}'");
			}
		}

	private function aggregate() : void
		{
		$buckets = [];

		foreach ($this->rawRows as $row)
			{
			$key = $this->rowFrameworkKey($row) . '|' . ($row['Description'] ?? '');
			$buckets[$key][] = $row;
			}

		$aggregated = [];

		foreach ($buckets as $bucket)
			{
			$bucket = $this->latestBatch($bucket);

			$warmRows = \array_values(\array_filter($bucket, fn(array $row) => $this->isWarmup($row)));
			$hotRows = \array_values(\array_filter($bucket, fn(array $row) => ! $this->isWarmup($row)));

			if ($hotRows === [])
				{
				$hotRows = $bucket;
				}

			$latest = $bucket[\array_key_last($bucket)];
			$warmup = $warmRows[0] ?? null;
			$item = [
				'Namespace' => $this->rowFrameworkKey($latest),
				'Test' => $latest['Test'] ?? '',
				'Framework Version' => $latest['Framework Version'] ?? FrameworkVersion::forTest($this->rowFrameworkKey($latest)),
				'Description' => $latest['Description'] ?? '',
				'Scenario ID' => $latest['Scenario ID'] ?? '',
				'Scenario Family' => $latest['Scenario Family'] ?? '',
				'Scenario Rows' => $latest['Scenario Rows'] ?? '',
				'Scenario Label' => $latest['Scenario Label'] ?? '',
				'System' => $latest['System'] ?? '',
				'PHP' => $latest['PHP'] ?? '',
				'Date/Time' => $latest['Date/Time'] ?? '',
				'Observed Runs' => \count($bucket),
				'Warmup Runs' => \count($warmRows),
				'Hot Runs' => \count($hotRows),
				'Configured Runs' => (int)($latest['Total Runs'] ?? \count($bucket)),
			];

			foreach (\array_keys(self::TIME_COLUMNS) as $col)
				{
				$item["avg:{$col}"] = $this->mean($hotRows, $col);
				$item["best:{$col}"] = $this->min($hotRows, $col);
				$item["warm:{$col}"] = $warmup ? (float)($warmup[$col] ?? 0) : null;
				}

			$aggregated[] = $item;
			}

		\usort($aggregated, fn(array $a, array $b) => $a['avg:Total Runtime Time'] <=> $b['avg:Total Runtime Time']);

		$this->rows = $aggregated;
		$this->assignRuntimePalette();
		$this->labelWidth = \max(
			\strlen('ORM / Backend'),
			...(\array_map(fn(array $row) => \strlen($this->labelForRow($row)), $this->rows))
		);
		}

	private function render() : void
		{
		$this->writeln();
		$this->writeln($this->tag(' PHP ORM Benchmark Results ', 'white', true));
		$this->writeln($this->tag(' Source: ' . $this->sourceSummary, 'bright-cyan'));
		if ($this->manifest !== [])
			{
			$this->writeln($this->tag(' Manifest: ' . $this->manifestSummary(), 'bright-white'));
			$this->writeln($this->tag(' Machine: ' . $this->machineSummary(), 'bright-white'));
			}
		$this->writeln($this->tag(' Aggregation: latest batch per benchmark key, hot-run averages, warmup shown separately when present', 'white'));
		$this->writeln($this->tag(' Rows loaded: ' . \count($this->rawRows) . '  ·  Benchmarks: ' . \count($this->rows), 'white'));
		$this->writeln();

		$this->renderSummaryTable();
		$this->renderBackendCharts();
		$this->renderEngineBreakdown();
		$this->renderRunStatusFooter();
		}

	private function renderSummaryTable() : void
		{
		$columns = [
			['label' => 'ORM / Backend', 'width' => $this->labelWidth, 'align' => 'left'],
			['label' => 'Runs', 'width' => 4, 'align' => 'right'],
			['label' => 'Warm', 'width' => 4, 'align' => 'right'],
			['label' => 'Total', 'width' => 8, 'align' => 'right'],
			['label' => 'Best', 'width' => 8, 'align' => 'right'],
			['label' => 'Warmup', 'width' => 8, 'align' => 'right'],
			['label' => 'Insert', 'width' => 8, 'align' => 'right'],
			['label' => 'Read', 'width' => 8, 'align' => 'right'],
			['label' => 'Update', 'width' => 8, 'align' => 'right'],
			['label' => 'Delete', 'width' => 8, 'align' => 'right'],
		];
		$line = $this->tableWidth($columns);

		$this->writeln($this->rule($line, '═'));
		$this->writeln($this->renderCells($columns, \array_map(
			fn(array $column) : string => $this->tag($column['label'], 'white', true),
			$columns
		)));
		$this->writeln($this->rule($line));

		foreach ($this->groupByScenario($this->rows) as $scenario => $scenarioRows)
			{
			$this->writeln($this->tag('  ' . $scenario, 'bright-white', true));
			$this->writeln($this->rule($line));

			foreach ($this->groupByBackend($scenarioRows) as $backend => $rows)
				{
				$this->writeln($this->tag('    ' . $backend, 'white', true));
				$this->writeln($this->rule($line));

				foreach ($rows as $row)
					{
					$totalAvg = (float)$row['avg:Total Runtime Time'];
					$totalBest = (float)$row['best:Total Runtime Time'];
					$warm = $row['warm:Total Runtime Time'];

					$this->writeln($this->renderCells($columns, [
						$this->labelCell($row),
						(string)(int)$row['Hot Runs'],
						(string)(int)$row['Warmup Runs'],
						$this->timeCell($totalAvg, 'avg:Total Runtime Time'),
						$this->timeCell($totalBest, 'best:Total Runtime Time'),
						$this->optionalTimeCell($warm),
						$this->timeCell((float)$row['avg:Insert Time'], 'avg:Insert Time'),
						$this->timeCell((float)$row['avg:Read Time'], 'avg:Read Time'),
						$this->timeCell((float)$row['avg:Update Time'], 'avg:Update Time'),
						$this->timeCell((float)$row['avg:Delete Time'], 'avg:Delete Time'),
					]));
					}

				$this->writeln();
				}
			}

		$this->writeln($this->tag(' Total/Insert/Read/Update/Delete columns are hot-run averages. Best is the fastest hot run.', 'bright-white'));
		$this->writeln();
		}

	private function renderBackendCharts() : void
		{
		$this->writeln($this->tag(' Total Runtime by Backend', 'white', true));
		$this->writeln();

		foreach ($this->groupByScenario($this->rows) as $scenario => $scenarioRows)
			{
			$this->writeln($this->tag('  ' . $scenario, 'bright-white', true));
			$this->writeln();

			foreach ($this->groupByBackend($scenarioRows) as $backend => $rows)
				{
				$max = \max(\array_map(fn(array $row) => (float)$row['avg:Total Runtime Time'], $rows));
				$this->writeln($this->tag('    ' . $backend, 'white', true));

				foreach ($rows as $row)
					{
					$total = (float)$row['avg:Total Runtime Time'];
					$warm = $row['warm:Total Runtime Time'];
					$delta = $warm ? $this->formatWarmupDelta((float)$warm, $total) : '';
					$runtimeColor = $this->runtimeColorForRow($row);
					$label = $this->chartLabelCell($row);

					$this->writeln(\sprintf(
						' %s  %s  %8s %s',
						$label,
						$this->bar($total, $max, 36, $runtimeColor),
						$this->ansi(\number_format($total, 3) . 's', $runtimeColor, true),
						$delta
					));
					}

				$this->writeln();
				}
			}
		}

	private function renderEngineBreakdown() : void
		{
		$frameworkWidth = $this->engineFrameworkWidth();
		$columns = [
			['label' => 'Framework', 'width' => $frameworkWidth, 'align' => 'left'],
			['label' => 'Rows', 'width' => 4, 'align' => 'right'],
			['label' => 'Total', 'width' => 8, 'align' => 'right'],
			['label' => 'Avg', 'width' => 8, 'align' => 'right'],
			['label' => 'Best', 'width' => 8, 'align' => 'right'],
			['label' => 'Insert', 'width' => 8, 'align' => 'right'],
			['label' => 'Read', 'width' => 8, 'align' => 'right'],
			['label' => 'Update', 'width' => 8, 'align' => 'right'],
			['label' => 'Delete', 'width' => 8, 'align' => 'right'],
		];
		$line = $this->tableWidth($columns);

		$this->writeln($this->tag(' Best Frameworks By Engine', 'white', true));
		$this->writeln($this->tag(' Totals are summed across all visible benchmark variants for each engine type.', 'bright-white'));
		$this->writeln();

		foreach ($this->rankFrameworksByEngine() as $engine => $frameworks)
			{
			$this->writeln($this->tag('  ' . $engine, 'white', true));
			$this->writeln($this->rule($line, '═'));
			$this->writeln($this->renderCells($columns, \array_map(
				fn(array $column) : string => $this->tag($column['label'], 'white', true),
				$columns
			)));
			$this->writeln($this->rule($line));

			$bestTotal = (float)$frameworks[0]['Total'];
			$worstTotal = (float)$frameworks[\array_key_last($frameworks)]['Total'];

			foreach ($frameworks as $framework)
				{
				$total = (float)$framework['Total'];
				$totalCell = $this->padPlain(\number_format($total, 3), 8, 'right');

				if (\abs($total - $bestTotal) < 0.0001)
					{
					$totalCell = $this->tag($totalCell, 'green');
					}
				elseif (\abs($total - $worstTotal) < 0.0001)
					{
					$totalCell = $this->tag($totalCell, 'red');
					}

				$this->writeln($this->renderCells($columns, [
					$this->paintOrm(
						(string)$framework['Test'],
						(string)($framework['Framework Version'] ?? ''),
						(string)($framework['Runtime Color'] ?? $this->runtimeColor((string)$framework['Test']))
					),
					(string)(int)$framework['Rows'],
					$totalCell,
					$this->padPlain(\number_format((float)$framework['Avg'], 3), 8, 'right'),
					$this->padPlain(\number_format((float)$framework['Best'], 3), 8, 'right'),
					$this->padPlain(\number_format((float)$framework['Insert'], 3), 8, 'right'),
					$this->padPlain(\number_format((float)$framework['Read'], 3), 8, 'right'),
					$this->padPlain(\number_format((float)$framework['Update'], 3), 8, 'right'),
					$this->padPlain(\number_format((float)$framework['Delete'], 3), 8, 'right'),
				]));
				}

			$this->writeln($this->rule($line));
			$this->writeln();
			}

		$this->writeln();
		}

	private function renderRunStatusFooter() : void
		{
		if ($this->manifest === [] && $this->footer === [])
			{
			return;
			}

		$line = 100;
		$lines = $this->footerMetricColumns(
			array_merge(
				$this->footerRunMetrics(),
				$this->footerSystemMetrics(),
				$this->footerHardwareMetrics()
			),
			array_merge(
				$this->footerConfigMetrics(),
				$this->footerStatusMetrics()
			),
			$line
		);

		$this->writeln($this->rule($line, '═'));
		foreach ($lines as $text)
			{
			$this->writeln($text);
			}

		$this->writeln($this->rule($line, '═'));
		$this->writeln();
		}

	/**
	 * @return array<string, list<array<string, float|int|string>>>
	 */
	private function rankFrameworksByEngine() : array
		{
		$engines = [];

		foreach ($this->rows as $row)
			{
			$engine = $this->backendGroup($row);
			$framework = $this->rowFrameworkKey($row);

			if (! isset($engines[$engine][$framework]))
				{
				$engines[$engine][$framework] = [
					'Test' => $framework,
					'Framework Version' => (string)($row['Framework Version'] ?? FrameworkVersion::forTest($framework)),
					'Rows' => 0,
					'Total' => 0.0,
					'Best' => 0.0,
					'Insert' => 0.0,
					'Read' => 0.0,
					'Update' => 0.0,
					'Delete' => 0.0,
					'Runtime Color' => $this->runtimeColor($framework),
				];
				}

			$engines[$engine][$framework]['Rows'] += 1;
			$engines[$engine][$framework]['Total'] += (float)$row['avg:Total Runtime Time'];
			$engines[$engine][$framework]['Best'] += (float)$row['best:Total Runtime Time'];
			$engines[$engine][$framework]['Insert'] += (float)$row['avg:Insert Time'];
			$engines[$engine][$framework]['Read'] += (float)$row['avg:Read Time'];
			$engines[$engine][$framework]['Update'] += (float)$row['avg:Update Time'];
			$engines[$engine][$framework]['Delete'] += (float)$row['avg:Delete Time'];
			}

		$ranked = [];

		foreach ($engines as $engine => $frameworks)
			{
			foreach ($frameworks as &$framework)
				{
				$framework['Avg'] = $framework['Rows'] > 0
					? $framework['Total'] / $framework['Rows']
					: 0.0;
				}
			unset($framework);

			$frameworks = \array_values($frameworks);
			\usort($frameworks, fn(array $a, array $b) => $a['Total'] <=> $b['Total']);
			$ranked[$engine] = $frameworks;
			}

		return $ranked;
		}

		/** @return list<string> */
	private function discoverRunFiles() : array
		{
		$files = \glob('results/results-*.csv') ?: [];
		$files = \array_values(\array_filter($files, 'is_file'));
		\sort($files);

		return $files;
		}

	/**
	 * @param list<string> $files
	 */
	private function latestRunFile(array $files) : string
		{
		usort($files, static fn(string $a, string $b) : int => filemtime($b) <=> filemtime($a));

		return $files[0];
		}

	private function resolveRequestedFile(string $requested) : ?string
		{
		if (\is_file($requested))
			{
			return $requested;
			}

		$matches = \glob('results/results-' . $requested . '-*.csv') ?: [];
		$matches = \array_values(\array_filter($matches, 'is_file'));

		if (\count($matches) === 1)
			{
			return $matches[0];
			}

		return null;
		}

	/**
	 * @return array<string, mixed>
	 */
	private function readManifest(string $file) : array
		{
		$stream = @\fopen($file, 'r');

		if (! \is_resource($stream))
			{
			return [];
			}

		while (($line = \fgets($stream)) !== false)
			{
			$trimmed = \trim($line);

			if ($trimmed === '')
				{
				continue;
				}

			if (! \str_starts_with($trimmed, '#'))
				{
				break;
				}

			if (\str_starts_with($trimmed, '# {'))
				{
				$decoded = \json_decode(\substr($trimmed, 2), true);
				\fclose($stream);

				return \is_array($decoded) ? $decoded : [];
				}
			}

		\fclose($stream);

		return [];
		}

	/**
	 * @return array<string, mixed>
	 */
	private function readFooter(string $file) : array
		{
		$lines = @\file($file, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

		if (! \is_array($lines))
			{
			return [];
			}

		for ($index = \count($lines) - 1; $index >= 1; --$index)
			{
			$trimmed = \trim($lines[$index]);
			$previous = \trim($lines[$index - 1] ?? '');

			if ($trimmed === '' || ! \str_starts_with($trimmed, '#'))
				{
				continue;
				}

			if ($previous === '# THEPHPBENCH-STATUS' && \str_starts_with($trimmed, '# {'))
				{
				$decoded = \json_decode(\substr($trimmed, 2), true);

				return \is_array($decoded) ? $decoded : [];
				}
			}

		return [];
		}

	private function manifestSummary() : string
		{
		$parts = [];

		foreach (['benchmark_run_id' => 'run', 'benchmark_type' => 'type', 'benchmark_label' => 'label', 'config_file' => 'config'] as $key => $label)
			{
			$value = (string)($this->manifest[$key] ?? '');

			if ($value !== '')
				{
				$parts[] = $label . ' ' . $value;
				}
			}

		$columnCount = (int)($this->manifest['column_count'] ?? 0);
		$schemaVersion = (int)($this->manifest['results_schema_version'] ?? 0);

		if ($schemaVersion > 0)
			{
			$parts[] = 'schema v' . $schemaVersion;
			}

		if ($columnCount > 0)
			{
			$parts[] = 'columns ' . $columnCount;
			}

		return \implode(', ', $parts);
		}

	private function statusSummary() : string
		{
		$parts = [];
		$status = (string)($this->footer['status'] ?? '');
		$isComplete = (bool)($this->footer['completed'] ?? false);
		$summaryState = $isComplete ? 'complete' : 'partial';

		$parts[] = $summaryState;

		if ($status !== '' && $status !== $summaryState)
			{
			$parts[] = 'state ' . $status;
			}

		$rowsWritten = (int)($this->footer['rows_written'] ?? 0);
		$queuedRuns = (int)($this->footer['queued_runs'] ?? 0);

		if ($queuedRuns > 0)
			{
			$parts[] = 'rows ' . $rowsWritten . '/' . $queuedRuns;
			}
		elseif ($rowsWritten > 0)
			{
			$parts[] = 'rows ' . $rowsWritten;
			}

		$elapsedSeconds = (float)($this->footer['elapsed_seconds'] ?? 0);

		if ($elapsedSeconds <= 0)
			{
			$startedAt = (string)($this->manifest['run_started_at'] ?? '');
			$updatedAt = (string)($this->footer['updated_at'] ?? '');
			$startedTimestamp = $startedAt !== '' ? \strtotime($startedAt) : false;
			$updatedTimestamp = $updatedAt !== '' ? \strtotime($updatedAt) : false;

			if ($startedTimestamp !== false && $updatedTimestamp !== false && $updatedTimestamp >= $startedTimestamp)
				{
				$elapsedSeconds = (float)($updatedTimestamp - $startedTimestamp);
				}
			}

		if ($elapsedSeconds > 0)
			{
			$parts[] = 'runtime ' . $this->formatDuration($elapsedSeconds);
			}

		$pid = (int)($this->manifest['runner_pid'] ?? 0);

		if ($pid > 0)
			{
			$parts[] = 'pid ' . $pid;
			}

		$check = (string)($this->manifest['runner_status_check'] ?? '');

		if ($check !== '')
			{
			$parts[] = 'check `' . $check . '`';
			}

		$updatedAt = (string)($this->footer['updated_at'] ?? '');

		if ($updatedAt !== '')
			{
			$parts[] = 'updated ' . $updatedAt;
			}

		return \implode(', ', $parts);
		}

	private function formatDuration(float $seconds) : string
		{
		$total = (int)\round($seconds);
		$hours = intdiv($total, 3600);
		$minutes = intdiv($total % 3600, 60);
		$secs = $total % 60;

		if ($hours > 0)
			{
			return sprintf('%dh%02dm%02ds', $hours, $minutes, $secs);
			}

		if ($minutes > 0)
			{
			return sprintf('%dm%02ds', $minutes, $secs);
			}

		return sprintf('%ds', $secs);
		}

	private function machineSummary() : string
		{
		if ($this->manifest === [])
			{
			return '';
			}

		$parts = [];

		$host = (string)($this->manifest['machine_host'] ?? '');
		$os = (string)($this->manifest['machine_os'] ?? '');
		$kernel = (string)($this->manifest['machine_kernel'] ?? '');
		$arch = (string)($this->manifest['machine_arch'] ?? '');

		if ($host !== '')
			{
			$parts[] = 'host ' . $host;
			}

		if ($os !== '')
			{
			$parts[] = 'os ' . $os;
			}

		if ($kernel !== '')
			{
			$parts[] = 'kernel ' . $kernel;
			}

		if ($arch !== '')
			{
			$parts[] = 'arch ' . $arch;
			}

		$cpu = (string)($this->manifest['machine_cpu_model'] ?? '');
		$cores = (int)($this->manifest['machine_cpu_cores'] ?? 0);

		if ($cpu !== '')
			{
			$parts[] = $cores > 0 ? "cpu {$cpu} ({$cores} cores)" : 'cpu ' . $cpu;
			}

		$memory = (int)($this->manifest['machine_memory_bytes'] ?? 0);

		if ($memory > 0)
			{
			$parts[] = 'memory ' . $this->formatBytes($memory);
			}

		$modules = (string)($this->manifest['machine_memory_modules'] ?? '');

		if ($modules !== '')
			{
			$parts[] = 'modules ' . $modules;
			}

		$disk = (float)($this->manifest['machine_disk_bytes'] ?? 0);

		if ($disk > 0)
			{
			$parts[] = 'disk ' . $this->formatBytes($disk);
			}

		$bootDisk = (string)($this->manifest['machine_boot_disk'] ?? '');

		if ($bootDisk !== '')
			{
			$parts[] = 'boot disk ' . $bootDisk;
			}

		return \implode(', ', $parts);
		}

	private function formatBytes(float|int $bytes) : string
		{
		$units = ['B', 'KiB', 'MiB', 'GiB', 'TiB'];
		$value = (float)$bytes;
		$unit = 0;

		while ($value >= 1024.0 && $unit < \count($units) - 1)
			{
			$value /= 1024.0;
			++$unit;
			}

		return \number_format($value, $value >= 100 ? 0 : 1) . $units[$unit];
		}


	/**
	 * @param list<string> $files
	 * @return array{files: list<string>, mode: string}
	 */
	private function selectCumulativeFiles(array $files) : array
		{
		$latestByLabel = [];

		foreach ($files as $file)
			{
			$rows = \iterator_to_array(new \ThePHPBench\CSV\FileReader($file), false);

			if ($rows === [])
				{
				continue;
				}

			$first = $rows[0];
			$type = (string)($first['Benchmark Type'] ?? '');
			$label = (string)($first['Benchmark Label'] ?? '');
			$timestamp = (string)($first['Benchmark Timestamp'] ?? '');

			if ($type !== 'standard-full' || $label === '')
				{
				continue;
				}

			$key = $label;

			if (! isset($latestByLabel[$key]) || $timestamp > $latestByLabel[$key]['timestamp'])
				{
				$latestByLabel[$key] = [
					'file' => $file,
					'timestamp' => $timestamp,
				];
				}
			}

		if ($latestByLabel === [])
			{
			return [
				'files' => [end($files) ?: $files[0]],
				'mode' => 'latest',
			];
			}

		return [
			'files' => \array_values(\array_map(
				static fn(array $item) : string => $item['file'],
				$latestByLabel
			)),
			'mode' => 'standard-full',
		];
		}

	/**
	 * @param list<array<string, mixed>> $rows
	 * @return array<string, list<array<string, mixed>>>
	 */
	private function groupByBackend(array $rows) : array
		{
		$groups = [];

		foreach ($rows as $row)
			{
			$key = $this->backendGroup($row);
			$groups[$key][] = $row;
			}

		return $groups;
		}

	/**
	 * @param list<array<string, mixed>> $rows
	 * @return array<string, list<array<string, mixed>>>
	 */
	private function groupByScenario(array $rows) : array
		{
		$groups = [];

		foreach ($rows as $row)
			{
			$key = $this->scenarioGroup($row);
			$groups[$key][] = $row;
			}

		return $groups;
		}

	private function backendGroup(array $row) : string
		{
		return BackendLabel::fromRow($row);
		}

	/**
	 * @param array<string, mixed> $row
	 */
	private function scenarioGroup(array $row) : string
		{
		$label = \trim((string)($row['Scenario Label'] ?? ''));
		$rows = \trim((string)($row['Scenario Rows'] ?? ''));

		if ($label !== '' && $rows !== '' && ! \preg_match('/(?:^|\s)' . \preg_quote($rows, '/') . '$/', $label))
			{
			return sprintf('%s %s', $label, $rows);
			}

		if ($label !== '')
			{
			return $label;
			}

		return (string)($row['Description'] ?? 'Unknown Scenario');
		}

	private function isWarmup(array $row) : bool
		{
		$value = \strtolower((string)($row['Warmup'] ?? ''));

		return \in_array($value, ['1', 'true', 'yes', 'y'], true);
		}

	/**
	 * @param list<array<string, string>> $bucket
	 * @return list<array<string, string>>
	 */
	private function latestBatch(array $bucket) : array
		{
		\usort($bucket, function(array $a, array $b) : int
			{
			$dateCmp = \strcmp($a['Date/Time'] ?? '', $b['Date/Time'] ?? '');

			if ($dateCmp !== 0)
				{
				return $dateCmp;
				}

			return ((int)($a['Run Number'] ?? 0)) <=> ((int)($b['Run Number'] ?? 0));
			});

		$hasRunNumbers = false;

		foreach ($bucket as $row)
			{
			if (isset($row['Run Number']))
				{
				$hasRunNumbers = true;
				break;
				}
			}

		if (! $hasRunNumbers)
			{
			return [$bucket[\array_key_last($bucket)]];
			}

		$batches = [];
		$current = [];

		foreach ($bucket as $row)
			{
			$run = (int)($row['Run Number'] ?? 0);

			if ($run === 1 && $current !== [])
				{
				$batches[] = $current;
				$current = [];
				}

			$current[] = $row;
			}

		if ($current !== [])
			{
			$batches[] = $current;
			}

		return $batches[\array_key_last($batches)] ?? $bucket;
		}

	/**
	 * @param list<array<string, string>> $rows
	 */
	private function mean(array $rows, string $col) : float
		{
		if ($rows === [])
			{
			return 0.0;
			}

		$total = 0.0;

		foreach ($rows as $row)
			{
			$total += (float)($row[$col] ?? 0);
			}

		return $total / \count($rows);
		}

	/**
	 * @param list<array<string, string>> $rows
	 */
	private function min(array $rows, string $col) : float
		{
		return \min(\array_map(fn(array $row) => (float)($row[$col] ?? 0), $rows) ?: [0.0]);
		}

	private function timeCell(float $value, string $metric) : string
		{
		$min = \min(\array_map(fn(array $row) => (float)$row[$metric], $this->rows) ?: [0.0]);
		$max = \max(\array_map(fn(array $row) => (float)$row[$metric], $this->rows) ?: [0.0]);
		$text = $this->padPlain(\number_format($value, 3), 8, 'right');

		if (\abs($value - $min) < 0.0001)
			{
			return $this->tag($text, 'green');
			}

		if (\abs($value - $max) < 0.0001)
			{
			return $this->tag($text, 'red');
			}

		return $text;
		}

	private function optionalTimeCell(?float $value) : string
		{
		return $value === null ? $this->tag($this->padPlain('-', 8, 'right'), 'bright-white') : $this->padPlain(\number_format($value, 3), 8, 'right');
		}

	private function formatWarmupDelta(float $warmup, float $hotAvg) : string
		{
		if ($hotAvg <= 0.0)
			{
			return '';
			}

		$delta = (($warmup / $hotAvg) - 1.0) * 100.0;
		$color = $delta > 0 ? 'yellow' : 'green';
		$prefix = $delta > 0 ? '+' : '';

		return $this->tag('warm ' . $prefix . \number_format($delta, 1) . '%', $color);
		}

	private function backendColor(string $backend) : string
		{
		$normalized = \strtolower($backend);

		return match (true) {
			\str_contains($normalized, 'postgres') => 'magenta',
			\str_contains($normalized, 'maria') => 'yellow',
			\str_contains($normalized, 'mysql') => 'blue',
			\str_contains($normalized, 'memory') => 'green',
			\str_contains($normalized, 'sqlite') => 'cyan',
			default => 'white',
		};
		}

	private function statusColor() : string
		{
		$status = \strtolower((string)($this->footer['status'] ?? ''));
		$isComplete = (bool)($this->footer['completed'] ?? false);

		if ($isComplete || $status === 'complete')
			{
			return 'bright-white';
			}

		if ($status === 'failed')
			{
			return 'bright-red';
			}

		return 'bright-yellow';
		}

	private function footerLabelColor() : string
		{
		return 'bright-cyan';
		}

	private function footerValueColor() : string
		{
		return 'bright-white';
		}

	private function footerNumberColor() : string
		{
		return $this->rgb(94, 234, 212);
		}

	/**
	 * @return list<string>
	 */
	private function footerKeyValueLines(string $label, string $value, int $width, ?string $valueColor = null, bool $boldValue = false) : array
		{
		if (\trim($value) === '')
			{
			return [];
			}

		$labelWidth = 10;
		$prefixPlain = $label . ': ';
		$valueWidth = \max(1, $width - $labelWidth);
		$wrapped = $this->wrapPlain($value, $valueWidth);
		$lines = [];

		foreach ($wrapped as $index => $chunk)
			{
			$plainLabel = 0 === $index ? $prefixPlain : '';
			$plainLabel = $this->padPlain($plainLabel, $labelWidth, 'left');
			$styledLabel = 0 === $index
				? $this->tag($this->padPlain($prefixPlain, $labelWidth, 'left'), $this->footerLabelColor(), true)
				: \str_repeat(' ', $labelWidth);
			$styledValue = $this->tag(
				$this->padPlain($chunk, $valueWidth, 'left'),
				$valueColor ?? $this->footerValueColor(),
				$boldValue
			);

			$lines[] = $styledLabel . $styledValue;
			}

		return $lines;
		}

	/**
	 * @return list<string>
	 */
	private function footerTwoColumnLines(
		string $leftLabel,
		string $leftValue,
		string $rightLabel,
		string $rightValue,
		int $width,
		?string $leftValueColor = null,
		?string $rightValueColor = null
	) : array
		{
		$gap = 4;
		$columnWidth = intdiv($width - $gap, 2);
		$left = $this->footerKeyValueLines($leftLabel, $leftValue, $columnWidth, $leftValueColor);
		$right = $this->footerKeyValueLines($rightLabel, $rightValue, $columnWidth, $rightValueColor, 'Status' === $rightLabel);
		$rows = max(count($left), count($right));
		$lines = [];

		for ($index = 0; $index < $rows; ++$index)
			{
			$leftCell = $left[$index] ?? str_repeat(' ', $columnWidth);
			$rightCell = $right[$index] ?? '';
			$lines[] = $leftCell . str_repeat(' ', $gap) . $rightCell;
			}

		return $lines;
		}

	/**
	 * @param list<array{label: string, value: string, color?: string, bold?: bool}> $leftMetrics
	 * @param list<array{label: string, value: string, color?: string, bold?: bool}> $rightMetrics
	 * @return list<string>
	 */
	private function footerMetricColumns(array $leftMetrics, array $rightMetrics, int $width) : array
		{
		$gap = 4;
		$rightColumnWidth = 28;
		$leftColumnWidth = max(20, $width - $gap - $rightColumnWidth);
		$rightMetrics = array_merge([[
			'label' => '',
			'value' => '',
		]], $rightMetrics);
		$rows = max(count($leftMetrics), count($rightMetrics));
		$lines = [];

		for ($index = 0; $index < $rows; ++$index)
			{
			$leftCell = isset($leftMetrics[$index])
				? $this->footerMetricCell($leftMetrics[$index], $leftColumnWidth, $this->rgb(255, 214, 102))
				: str_repeat(' ', $leftColumnWidth);
			$rightCell = isset($rightMetrics[$index])
				? $this->footerMetricCell($rightMetrics[$index], $rightColumnWidth, null, true)
				: '';
			$lines[] = $leftCell . str_repeat(' ', $gap) . $rightCell;
			}

		return $lines;
		}

	/**
	 * @return list<array{label: string, value: string, color?: string, bold?: bool}>
	 */
	private function footerLabeledMetrics(string $label, string $value, ?string $color = null, bool $bold = false) : array
		{
		if (\trim($value) === '')
			{
			return [];
			}

		return [[
			'label' => $label,
			'value' => $value,
			'color' => $color ?? $this->footerValueColor(),
			'bold' => $bold,
		]];
		}

	/**
	 * @param array{label: string, value: string, color?: string, bold?: bool} $metric
	 */
	private function footerMetricCell(array $metric, int $width, ?string $defaultValueColor = null, bool $rightAlign = false) : string
		{
		$valueColor = (string)($metric['color'] ?? $defaultValueColor ?? $this->footerValueColor());

		if ($rightAlign)
			{
			$labelWidth = 10;
			$labelText = $metric['label'] === '' ? '' : $metric['label'] . ':';
			$plainLabel = $metric['label'] === '' ? '' : $this->padPlain($labelText, $labelWidth, 'right') . ' ';
			$plain = $plainLabel . $metric['value'];
			$padding = str_repeat(' ', max(0, $width - strlen($plain)));
			$styledLabel = $metric['label'] === ''
				? ''
				: $this->tag($this->padPlain($labelText, $labelWidth, 'right') . ' ', $this->footerLabelColor(), true);
			$styledValue = str_starts_with($valueColor, "\033[")
				? $this->ansi($metric['value'], $valueColor, (bool)($metric['bold'] ?? false))
				: $this->tag($metric['value'], $valueColor, (bool)($metric['bold'] ?? false));

			return $padding . $styledLabel . $styledValue;
			}

		$labelWidth = 10;
		$labelText = $metric['label'] === '' ? '' : $metric['label'] . ':';
		$label = $this->tag($this->padPlain($labelText, $labelWidth, 'left'), $this->footerLabelColor(), true);
		$valueWidth = max(1, $width - $labelWidth);
		$valueText = $this->padPlain($metric['value'], $valueWidth, 'left');
		$value = str_starts_with($valueColor, "\033[")
			? $this->ansi($valueText, $valueColor, (bool)($metric['bold'] ?? false))
			: $this->tag($valueText, $valueColor, (bool)($metric['bold'] ?? false));

		return $label . $value;
		}

	private function footerRunSummary() : string
		{
		$parts = [];
		$runId = (string)($this->manifest['benchmark_run_id'] ?? '');
		$type = (string)($this->manifest['benchmark_type'] ?? '');
		$label = (string)($this->manifest['benchmark_label'] ?? '');

		if ($runId !== '')
			{
			$parts[] = 'id ' . $runId;
			}
		if ($type !== '')
			{
			$parts[] = 'type ' . $type;
			}
		if ($label !== '')
			{
			$parts[] = 'label ' . $label;
			}

		return \implode(', ', $parts);
		}

	/**
	 * @return list<array{label: string, value: string, color?: string, bold?: bool}>
	 */
	private function footerRunMetrics() : array
		{
		$metrics = [];
		$runId = (string)($this->manifest['benchmark_run_id'] ?? '');
		$type = (string)($this->manifest['benchmark_type'] ?? '');
		$label = (string)($this->manifest['benchmark_label'] ?? '');

		if ($runId !== '')
			{
			$metrics[] = [
				'label' => 'Run ID',
				'value' => $runId . ($this->sourceSummary !== '' ? ' - ' . \basename($this->sourceSummary) : ''),
				'color' => $this->footerNumberColor(),
				'bold' => true,
			];
			}
		if ($type !== '')
			{
			$metrics[] = [
				'label' => 'Type',
				'value' => $type,
			];
			}
		if ($label !== '')
			{
			$metrics[] = [
				'label' => 'Label',
				'value' => $label,
			];
			}

		return $metrics;
		}

	private function footerConfigSummary() : string
		{
		$parts = [];
		$config = (string)($this->manifest['config_file'] ?? '');
		$schema = (int)($this->manifest['results_schema_version'] ?? 0);
		$columns = (int)($this->manifest['column_count'] ?? 0);

		if ($config !== '')
			{
			$parts[] = $config;
			}
		if ($schema > 0)
			{
			$parts[] = 'schema v' . $schema;
			}
		if ($columns > 0)
			{
			$parts[] = 'columns ' . $columns;
			}

		return \implode(', ', $parts);
		}

	/**
	 * @return list<array{label: string, value: string, color?: string, bold?: bool}>
	 */
	private function footerConfigMetrics() : array
		{
		$metrics = [];
		$config = (string)($this->manifest['config_file'] ?? '');
		$schema = (int)($this->manifest['results_schema_version'] ?? 0);
		$columns = (int)($this->manifest['column_count'] ?? 0);

		if ($config !== '')
			{
			$metrics[] = [
				'label' => 'Config',
				'value' => $config,
				'color' => $this->footerNumberColor(),
				'bold' => true,
			];
			}
		if ($schema > 0)
			{
			$metrics[] = [
				'label' => 'Schema',
				'value' => 'v' . $schema,
				'color' => $this->footerNumberColor(),
				'bold' => true,
			];
			}
		if ($columns > 0)
			{
			$metrics[] = [
				'label' => 'Columns',
				'value' => (string)$columns,
				'color' => $this->footerNumberColor(),
				'bold' => true,
			];
			}

		return $metrics;
		}

	private function footerSystemSummary() : string
		{
		$parts = [];

		foreach ([
			'machine_host' => 'host',
			'machine_os' => 'os',
			'machine_kernel' => 'kernel',
			'machine_arch' => 'arch',
		] as $key => $label)
			{
			$value = (string)($this->manifest[$key] ?? '');

			if ($value !== '')
				{
				$parts[] = $label . ' ' . $value;
				}
			}

		return \implode(', ', $parts);
		}

	private function footerHardwareSummary() : string
		{
		$parts = [];
		$cpu = (string)($this->manifest['machine_cpu_model'] ?? '');
		$cores = (int)($this->manifest['machine_cpu_cores'] ?? 0);
		$memory = (int)($this->manifest['machine_memory_bytes'] ?? 0);
		$modules = (string)($this->manifest['machine_memory_modules'] ?? '');
		$disk = (float)($this->manifest['machine_disk_bytes'] ?? 0);
		$bootDisk = (string)($this->manifest['machine_boot_disk'] ?? '');

		if ($cpu !== '')
			{
			$parts[] = $cores > 0 ? "cpu {$cpu} ({$cores} cores)" : 'cpu ' . $cpu;
			}
		if ($memory > 0)
			{
			$parts[] = 'memory ' . $this->formatBytes($memory);
			}
		if ($modules !== '')
			{
			$parts[] = 'modules ' . $modules;
			}
		if ($disk > 0)
			{
			$parts[] = 'disk ' . $this->formatBytes($disk);
			}
		if ($bootDisk !== '')
			{
			$parts[] = 'boot ' . $bootDisk;
			}

		return \implode(', ', $parts);
		}

	/**
	 * @return list<array{label: string, value: string, color?: string, bold?: bool}>
	 */
	private function footerSystemMetrics() : array
		{
		$metrics = [];
		$host = (string)($this->manifest['machine_host'] ?? '');
		$os = (string)($this->manifest['machine_os'] ?? '');
		$kernel = (string)($this->manifest['machine_kernel'] ?? '');
		$arch = (string)($this->manifest['machine_arch'] ?? '');

		if ($host !== '')
			{
			$metrics[] = [
				'label' => 'Host',
				'value' => $host,
			];
			}
		if ($os !== '')
			{
			$osValue = $os;

			if ($kernel !== '')
				{
				$osValue .= ' ' . $kernel;
				}

			if ($arch !== '')
				{
				$osValue .= ' ' . $arch;
				}

			$metrics[] = [
				'label' => 'OS',
				'value' => $osValue,
				'color' => $this->footerNumberColor(),
				'bold' => true,
			];
			}

		return $metrics;
		}

	private function footerCountsSummary() : string
		{
		return 'rows ' . \count($this->rawRows) . ', benchmarks ' . \count($this->rows);
		}

	/**
	 * @return list<array{label: string, value: string, color?: string, bold?: bool}>
	 */
	private function footerHardwareMetrics() : array
		{
		$metrics = [];
		$cpu = (string)($this->manifest['machine_cpu_model'] ?? '');
		$cores = (int)($this->manifest['machine_cpu_cores'] ?? 0);
		$memory = (int)($this->manifest['machine_memory_bytes'] ?? 0);
		$modules = (string)($this->manifest['machine_memory_modules'] ?? '');
		$disk = (float)($this->manifest['machine_disk_bytes'] ?? 0);
		$bootDisk = (string)($this->manifest['machine_boot_disk'] ?? '');

		if ($cpu !== '')
			{
			$metrics[] = [
				'label' => 'CPU',
				'value' => $cores > 0 ? $cpu . ' (' . $cores . ' cores)' : $cpu,
			];
			}
		if ($memory > 0)
			{
			$metrics[] = [
				'label' => 'Memory',
				'value' => $this->formatBytes($memory),
				'color' => $this->rgb(255, 214, 102),
				'bold' => true,
			];
			}
		if ($disk > 0)
			{
			$metrics[] = [
				'label' => 'Disk',
				'value' => $this->formatBytes($disk),
				'color' => $this->rgb(255, 214, 102),
				'bold' => true,
			];
			}
		if ($bootDisk !== '')
			{
			$metrics[] = [
				'label' => 'Boot',
				'value' => $bootDisk,
			];
			}
		if ($modules !== '')
			{
			$moduleParts = array_values(array_filter(array_map('trim', explode('|', $modules)), static fn(string $part) : bool => $part !== ''));

			if ($moduleParts === [])
				{
				$moduleParts = [$modules];
				}

			foreach ($moduleParts as $index => $module)
				{
				$metrics[] = [
					'label' => 0 === $index ? 'DIMMs' : '',
					'value' => $module,
				];
				}
			}

		return $metrics;
		}

	/**
	 * @return list<array{label: string, value: string, color?: string, bold?: bool}>
	 */
	private function footerStatusMetrics() : array
		{
		$metrics = [
			[
				'label' => 'Rows',
				'value' => (string)\count($this->rawRows),
				'color' => $this->footerNumberColor(),
				'bold' => true,
			],
			[
				'label' => 'Bench',
				'value' => (string)\count($this->rows),
				'color' => $this->footerNumberColor(),
				'bold' => true,
			],
		];

		$elapsedSeconds = (float)($this->footer['elapsed_seconds'] ?? 0);

		if ($elapsedSeconds <= 0)
			{
			$startedAt = (string)($this->manifest['run_started_at'] ?? '');
			$updatedAt = (string)($this->footer['updated_at'] ?? '');
			$startedTimestamp = $startedAt !== '' ? \strtotime($startedAt) : false;
			$updatedTimestamp = $updatedAt !== '' ? \strtotime($updatedAt) : false;

			if ($startedTimestamp !== false && $updatedTimestamp !== false && $updatedTimestamp >= $startedTimestamp)
				{
				$elapsedSeconds = (float)($updatedTimestamp - $startedTimestamp);
				}
			}

		if ($elapsedSeconds > 0)
			{
			$metrics[] = [
				'label' => 'Runtime',
				'value' => $this->formatDuration($elapsedSeconds),
				'color' => $this->footerNumberColor(),
				'bold' => true,
			];
			}

		$metrics[] = [
			'label' => 'Status',
			'value' => (bool)($this->footer['completed'] ?? false) ? 'complete' : ((string)($this->footer['status'] ?? 'partial') ?: 'partial'),
			'color' => $this->footerNumberColor(),
			'bold' => true,
		];

		return $metrics;
		}

	private function tag(string $text, string $color, bool $bold = false) : string
		{
		$options = $bold ? "options=bold;fg={$color}" : "fg={$color}";

		return "<{$options}>{$text}</>";
		}

	private function rule(int $width, string $char = '─') : string
		{
		return $this->tag(\str_repeat($char, $width), 'bright-cyan');
		}

	private function runtimeColorForRow(array $row) : string
		{
		return $this->runtimeColor($this->rowFrameworkKey($row));
		}

	private function runtimeColor(string $framework) : string
		{
		$key = $this->runtimeKey($framework);
		$code = $this->palette[$key] ?? self::RUNTIME_PALETTE[0];

		return "\033[38;5;{$code}m";
		}

	private function paintOrm(string $orm, ?string $version = null, ?string $color = null) : string
		{
		return $this->ansi(FrameworkVersion::label($orm, $version), $color, true);
		}

	/**
	 * @param array<string, mixed> $row
	 */
	private function rowFrameworkKey(array $row) : string
		{
		$namespace = \trim((string)($row['Namespace'] ?? ''));

		if ($namespace !== '')
			{
			return $namespace;
			}

		return (string)($row['Test'] ?? '');
		}

	/**
	 * @param array<string, mixed> $row
	 */
	private function labelForRow(array $row) : string
		{
		return FrameworkVersion::label(
			$this->rowFrameworkKey($row),
			(string)($row['Framework Version'] ?? FrameworkVersion::forTest($this->rowFrameworkKey($row)))
		) . ' / ' . (string)$row['Description'] . $this->runProgressSuffix($row);
		}

	/**
	 * @param array<string, mixed> $row
	 */
	private function runProgressSuffix(array $row) : string
		{
		$observedRuns = (int)($row['Observed Runs'] ?? 0);
		$configuredRuns = (int)($row['Configured Runs'] ?? 0);

		if ($observedRuns > 0 && $configuredRuns > 0)
			{
			return sprintf(' [run %d/%d]', min($observedRuns, $configuredRuns), $configuredRuns);
			}

		return '';
		}

	private function bar(float $value, float $max, int $width, string $color) : string
		{
		if ($max <= 0.0)
			{
			return \str_repeat(' ', $width);
			}

		$filled = \max(1, \min($width, (int)\round(($value / $max) * $width)));

		return $this->ansi(\str_repeat('█', $filled), $color, true) . \str_repeat(' ', $width - $filled);
		}

	private function writeln(string $text = '') : void
		{
		$this->output->writeln($text);
		}

	/**
	 * @param array<string, mixed> $row
	 */
	private function labelCell(array $row) : string
		{
		$orm = $this->rowFrameworkKey($row);
		$backend = ' / ' . (string)$row['Description'];
		$progress = $this->runProgressSuffix($row);
		$plain = $this->labelForRow($row);
		$padding = \str_repeat(' ', \max(0, $this->labelWidth - \strlen($plain)));
		$color = $this->runtimeColorForRow($row);

		return $this->paintOrm($orm, (string)($row['Framework Version'] ?? FrameworkVersion::forTest($orm)), $color) . $backend . $this->tag($progress, 'bright-white') . $padding;
		}

	/**
	 * @param array<string, mixed> $row
	 */
	private function chartLabelCell(array $row) : string
		{
		$orm = $this->rowFrameworkKey($row);
		$backend = ' / ' . (string)$row['Description'];
		$progress = $this->runProgressSuffix($row);
		$plain = $this->labelForRow($row);
		$padding = \str_repeat(' ', \max(0, $this->labelWidth - \strlen($plain)));
		$color = $this->runtimeColorForRow($row);

		return $this->paintOrm($orm, (string)($row['Framework Version'] ?? FrameworkVersion::forTest($orm)), $color) . $backend . $this->tag($progress, 'bright-white') . $padding;
		}

	private function engineFrameworkWidth() : int
		{
		$frameworks = [];

		foreach ($this->rankFrameworksByEngine() as $engineFrameworks)
			{
			foreach ($engineFrameworks as $framework)
				{
				$frameworks[] = FrameworkVersion::label(
					(string)$framework['Test'],
					(string)($framework['Framework Version'] ?? '')
				);
				}
			}

		return \max(
			\strlen('Framework'),
			...(\array_map('strlen', $frameworks ?: ['Framework']))
		);
		}

	/**
	 * @param list<array{label: string, width: int, align: string}> $columns
	 * @param list<string> $cells
	 */
	private function renderCells(array $columns, array $cells) : string
		{
		$parts = [];

		foreach ($columns as $index => $column)
			{
			$parts[] = $this->padCell($cells[$index] ?? '', $column['width'], $column['align']);
			}

		return ' ' . \implode('  ', $parts);
		}

	private function padCell(string $value, int $width, string $align) : string
		{
		$visible = $this->stripTags($value);
		$padding = \max(0, $width - \strlen($visible));

		return match ($align) {
			'right' => \str_repeat(' ', $padding) . $value,
			default => $value . \str_repeat(' ', $padding),
		};
		}

	private function stripTags(string $value) : string
		{
		$value = (string)\preg_replace('/<[^>]+>/', '', $value);

		return (string)\preg_replace('/\e\[[0-9;]*m/', '', $value);
		}

	private function assignRuntimePalette() : void
		{
		$runtimeKeys = [];

		foreach ($this->rows as $row)
			{
			$runtimeKeys[$this->runtimeKey($this->rowFrameworkKey($row))] = true;
			}

		$this->palette = [];
		$index = 0;

		foreach (\array_keys($runtimeKeys) as $key)
			{
			$this->palette[$key] = self::RUNTIME_PALETTE[$index % \count(self::RUNTIME_PALETTE)];
			++$index;
			}
		}

	private function runtimeKey(string $framework) : string
		{
		return $framework;
		}

	private function ansi(string $text, ?string $color = null, bool $bold = false) : string
		{
		$prefix = '';

		if ($bold)
			{
			$prefix .= "\033[1m";
			}

		if ($color !== null && $color !== '')
			{
			$prefix .= $color;
			}

		return $prefix . $text . "\033[0m";
		}

	private function rgb(int $red, int $green, int $blue) : string
		{
		return "\033[38;2;{$red};{$green};{$blue}m";
		}

	private function padPlain(string $value, int $width, string $align = 'left') : string
		{
		$padding = \max(0, $width - \strlen($value));

		return 'right' === $align
			? \str_repeat(' ', $padding) . $value
			: $value . \str_repeat(' ', $padding);
		}

	/**
	 * @return list<string>
	 */
	private function wrapPlain(string $value, int $width) : array
		{
		$wrapped = \wordwrap($value, $width, "\n", true);
		$lines = \preg_split("/\r?\n/", $wrapped) ?: [];

		return \array_values(\array_filter($lines, static fn(string $line) : bool => $line !== ''));
		}

	/**
	 * @param list<array{label: string, width: int, align: string}> $columns
	 */
	private function tableWidth(array $columns) : int
		{
		$width = 1;

		foreach ($columns as $index => $column)
			{
			$width += $column['width'];

			if ($index > 0)
				{
				$width += 2;
				}
			}

		return $width;
		}
	}
