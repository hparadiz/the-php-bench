<?php

declare(strict_types=1);

namespace ThePHPBench;

use ThePHPBench\Benchmark\OperationPattern;
use ThePHPBench\Benchmark\OperationRegistry;

/**
 * Runs a single benchmark test in an isolated process.
 *
 * Invoked by BenchmarkCommand via ReactPHP ChildProcess.
 * Receives a base64-encoded JSON config blob as its sole argument.
 * Emits one JSON line to stdout on completion, progress lines to stderr.
 */
class Worker
	{
	public static function run(string $encodedConfig) : never
		{
		try
			{
			$config = json_decode(base64_decode($encodedConfig), true);

			if (! is_array($config))
				{
				fwrite(STDERR, "Worker: invalid config payload\n");
				exit(1);
				}

			$configObj   = new Configuration($config['test'], $config['iterations']);
			$namespace   = $configObj->getNamespace();
			$class       = "\\ThePHPBench\\{$namespace}\\Tests";
			$tester      = new $class();
			$runNumber   = max(1, (int)($config['runNumber'] ?? 1));
			$totalRuns   = max($runNumber, (int)($config['totalRuns'] ?? $runNumber));

			if (! $tester->dbSupported($configObj))
				{
				fwrite(STDERR, "{$namespace} does not support {$configObj->getDriver()}\n");
				// Emit a skip marker so the parent knows this was intentional
				echo json_encode([
					'skipped' => true,
					'namespace' => $namespace,
					'test' => DisplayName::forFramework($namespace),
					'description' => $configObj->getDescription(),
					'run_number' => $runNumber,
					'warmup' => 1 === $runNumber,
				]) . "\n";
				exit(0);
				}

			$runner = new WorkerRunner($tester, $configObj, $runNumber, $totalRuns);
			$result = $runner->execute();

			echo json_encode($result) . "\n";
			exit(0);
			}
		catch (\Throwable $e)
			{
			fwrite(STDERR, "Worker failed: {$e->getMessage()}\n");
			exit(1);
			}
		}
	}

/**
 * Executes all benchmark phases for a single Configuration and returns the
 * result array that mirrors what TestRunner used to write to the CSV.
 */
class WorkerRunner
	{
	/** @var array<int,int> */
	private array $insertedIds = [];

	public function __construct(
		private readonly Test          $tester,
		private readonly Configuration $config,
		private readonly int           $runNumber,
		private readonly int           $totalRuns,
	) {
		}

	/** @return array<string,mixed> */
	public function execute() : array
		{
		$system = php_uname();
		$host   = strtoupper(gethostname());
		$system = str_replace($host . ' ', '', $system);

		$result = [
			'Date/Time'           => date('Y-m-d H:i:s'),
			'System'              => $system,
			'PHP'                 => PHP_VERSION,
			'Benchmark Run ID'    => (string)($this->config->getRaw()['benchmark_run_id'] ?? ''),
			'Benchmark Type'      => (string)($this->config->getRaw()['benchmark_type'] ?? ''),
			'Benchmark Label'     => (string)($this->config->getRaw()['benchmark_label'] ?? ''),
			'Benchmark Timestamp' => (string)($this->config->getRaw()['benchmark_timestamp'] ?? ''),
			'Namespace'           => $this->config->getNamespace(),
			'Test'                => DisplayName::forFramework($this->config->getNamespace()),
			'Driver'              => $this->config->getDriver(),
			'Backend'             => BackendLabel::forDriver($this->config->getDriver(), $this->config->getDescription()),
			'Description'         => $this->config->getDescription(),
			'Scenario ID'         => $this->config->getScenario()->id,
			'Scenario Family'     => $this->config->getScenario()->modelFamily,
			'Scenario Rows'       => $this->config->getScenario()->rowCount,
			'Scenario Label'      => $this->scenarioLabel(),
			'Run Number'          => $this->runNumber,
			'Total Runs'          => $this->totalRuns,
			'Warmup'              => 1 === $this->runNumber ? 'yes' : 'no',
		];

		$lines   = $this->tester->getSchemaLines($this->config);
		$runTime = new BaseLine();
		$start   = new BaseLine();

		$this->tester->init($this->config, $lines, $runTime);
		$this->record($result, 'Init', $start);

		$this->phaseInsert($result);
		$this->phaseReads($result);
		$this->phaseUpdates($result);
		$this->phaseDelete($result);

		$this->record($result, 'Total Runtime', $runTime);
		$this->tester->closeConnection();

		return $result;
		}

	/** @param array<string,mixed> $result */
	private function phaseInsert(array &$result) : void
		{
		fwrite(STDERR, "  insert\n");
		$timer = new BaseLine();
		$id    = 0;

		for ($i = 1; $i <= $this->config->getIterations(); ++$i)
			{
			$newId = $this->tester->insert($i);

			if ($newId <= $id)
				{
				throw new \RuntimeException("Insert failed at iteration {$i}: returned id {$newId}");
				}
			$id = $newId;
			$this->insertedIds[$i] = $newId;
			}

		$this->tester->flush();
		$this->record($result, 'Insert', $timer);
		}

	/** @param array<string,mixed> $result */
	private function phaseReads(array &$result) : void
		{
		fwrite(STDERR, "  read patterns\n");
		$total = new BaseLine();

		foreach (OperationRegistry::readPatterns($this->config->getIterations()) as $pattern)
			{
			$this->runReadPattern($result, $pattern);
			}

		$this->record($result, 'Read', $total);
		$result['Random Read Time'] = $result['Read Rand 1 Time'] ?? 0.0;
		$result['Random Read Memory'] = $result['Read Rand 1 Memory'] ?? 0;
		}

	/** @param array<string,mixed> $result */
	private function phaseUpdates(array &$result) : void
		{
		fwrite(STDERR, "  update patterns\n");
		$total = new BaseLine();
		$patternIndex = 0;

		foreach (OperationRegistry::updatePatterns($this->config->getIterations()) as $pattern)
			{
			$this->runUpdatePattern($result, $pattern, ++$patternIndex);
			}

		$this->record($result, 'Update', $total);
		$result['Update Test Time'] = $result['Update All Verify Time'] ?? 0.0;
		$result['Update Test Memory'] = $result['Update All Verify Memory'] ?? 0;
		}

	/** @param array<string,mixed> $result */
	private function phaseDelete(array &$result) : void
		{
		fwrite(STDERR, "  delete\n");
		$timer = new BaseLine();

		for ($i = 1; $i <= $this->config->getIterations(); ++$i)
			{
			$this->tester->delete($this->insertedIds[$i] ?? $i);
			}

		$this->tester->flush();

		for ($i = 1; $i <= $this->config->getIterations(); ++$i)
			{
			if ($this->tester->read($this->insertedIds[$i] ?? $i) !== null)
				{
				throw new \RuntimeException("Delete verification failed at id " . ($this->insertedIds[$i] ?? $i));
				}
			}

		$this->record($result, 'Delete', $timer);
		}

	/** @param array<string,mixed> $result */
	private function runReadPattern(array &$result, OperationPattern $pattern) : void
		{
		fwrite(STDERR, "    {$pattern->label}\n");
		$timer = new BaseLine();

		foreach ($pattern->logicalIds as $logicalId)
			{
			$this->tester->verifyInitialState($this->tester->read($this->insertedIds[$logicalId] ?? $logicalId), $logicalId);
			}

		$this->tester->flush();
		$this->record($result, $pattern->label, $timer);
		}

	/** @param array<string,mixed> $result */
	private function runUpdatePattern(array &$result, OperationPattern $pattern, int $patternIndex) : void
		{
		fwrite(STDERR, "    {$pattern->label}\n");
		$timer = new BaseLine();
		$offsetBase = $this->updateOffsetBase($patternIndex);
		$expected = [];

		foreach ($pattern->logicalIds as $logicalId)
			{
			$to = $offsetBase + $logicalId;
			$this->tester->update($this->insertedIds[$logicalId] ?? $logicalId, $to);
			$expected[$logicalId] = $to;
			}

		$this->tester->flush();
		$this->record($result, $pattern->label, $timer);

		$verify = new BaseLine();

		foreach ($expected as $logicalId => $to)
			{
			$this->tester->verifyUpdatedState($this->tester->read($this->insertedIds[$logicalId] ?? $logicalId), $to);
			}

		$this->record($result, $pattern->label . ' Verify', $verify);
		}

	private function updateOffsetBase(int $patternIndex) : int
		{
		return $patternIndex * $this->config->getIterations();
		}

	/**
	 * @param array<string,mixed> $result
	 */
	private function record(array &$result, string $label, BaseLine $timer) : void
		{
		$result[$label . ' Time']   = $timer->stop();
		$result[$label . ' Memory'] = $timer->getMemory();
		}

	private function scenarioLabel() : string
		{
		$scenario = $this->config->getScenario();
		$family = match ($scenario->modelFamily) {
			'simple' => 'Simple',
			'canary' => 'Canary',
			'float_fixed' => 'FloatFixed',
			'int_fixed' => 'IntFixed',
			'string_fixed' => 'StringFixed',
			'string_variable' => 'StringVariable',
			default => $scenario->modelFamily,
		};

		return 'simple' === $scenario->modelFamily ? 'Simple' : "{$family} {$scenario->rowCount}";
		}
	}
