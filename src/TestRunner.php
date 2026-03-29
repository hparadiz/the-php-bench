<?php

namespace ThePHPBench;

class TestRunner
	{
	/**
	 * @var array<string,mixed>
	 */
	private array $currentResults = [];

	/**
	 * @var array<int,int>
	 */
	private array $insertedIds = [];

	/**
	 * @var array<int>
	 */
	private array $random = [];

	/**
	 * @var array<array<string,mixed>>
	 */
	private array $results = [];

	private int $updateOffset = 0;

	public function __construct(private \ThePHPBench\Configurations $configurations)
		{
		$iterations = $configurations->getIterations();
		$this->updateOffset = \random_int($iterations + 1, $iterations * 3000);
		$count = (int)($iterations / 10);

		for ($i = 0; $i < $count; ++$i)
			{
			$this->random[] = \random_int(1, $iterations);
			}
		}

	/**
	 * @param array<string,mixed> $row
	 */
	public function addResults(array $row) : static
		{
		$this->results[] = $row;

		return $this;
		}

	/**
	 * @return array<array<string,mixed>>
	 */
	public function getResults() : array
		{
		return $this->results;
		}

	/**
	 * @param array<int> $indexedTestsToRun indexes of specific tests to run
	 */
	public function runTests(array $indexedTestsToRun = []) : bool
		{
		$index = -1;

		foreach ($this->configurations as $config)
			{
			++$index;

			if ($indexedTestsToRun && ! \in_array($index, $indexedTestsToRun))
				{
				continue;
				}
			echo "Running test {$index}. ";
			$namespace = $config->getNameSpace();
			$class = "\\ThePHPBench\\{$namespace}\\Tests";
			$tester = new $class();

			if ($tester->dbSupported($config))
				{
				$this->test($tester, $config);
				$this->addResults($this->currentResults);
				$tester->closeConnection();
				}
			else
				{
				echo "{$namespace} does not support {$config->getDriver()}\n";
				}
			$tester = null;
			}

		return true;
		}

	private function setResult(string $test, \ThePHPBench\BaseLine $timer) : static
		{
		$duration = $timer->stop();
		$memory = $timer->getMemory();

		$this->currentResults[$test . ' Time'] = $duration;
		$this->currentResults[$test . ' Memory'] = $memory;

		return $this;
		}

	private function test(\ThePHPBench\Test $tester, \ThePHPBench\Configuration $config) : bool
		{
		// remove machine name
		$system = \php_uname();
		$host = \strtoupper(\gethostname());
		$system = \str_replace($host . ' ', '', $system);

		$this->currentResults = [
			'Date/Time' => \date('Y-m-d H:i:s'),
			'System' => $system,
			'PHP' => PHP_VERSION,
			'Namespace' => $config->getNamespace(),
			'Test' => DisplayName::forFramework($config->getNamespace()),
			'Driver' => $config->getDriver(),
			'Backend' => BackendLabel::forDriver($config->getDriver(), $config->getDescription()),
			'Description' => $config->getDescription(),
		];

		echo DisplayName::forFramework($config->getNamespace()) . " -> {$config->getDescription()}\n";

		$lines = $tester->getSchemaLines($config);

		$runTime = new \ThePHPBench\BaseLine();
		$start = new \ThePHPBench\BaseLine();
		$tester->init($config, $lines, $runTime);
		$this->setResult('Init', $start);

		$iterations = $config->getIterations();

		$timer = new \ThePHPBench\BaseLine();

		echo "Executing insert test\n";

		$id = 0;
		$this->insertedIds = [];

		for ($i = 1; $i <= $iterations; ++$i)
			{
			$newId = $tester->insert($i);

			if ($newId <= $id)
				{
				throw new \Exception('Failed to insert ' . $newId);
				}
			$id = $newId;
			$this->insertedIds[$i] = $newId;
			}
		$tester->flush();
		$this->setResult('Insert', $timer);

		$timer = new \ThePHPBench\BaseLine();

		echo "Executing update test\n";

		for ($i = 1; $i <= $iterations; ++$i)
			{
			$tester->verifyInitialState($tester->read($this->insertedIds[$i] ?? $i), $i);
			}
		$tester->flush();
		$this->setResult('Read', $timer);

		$timer = new \ThePHPBench\BaseLine();

		for ($i = 1; $i <= $iterations; ++$i)
			{
			$tester->update($this->insertedIds[$i] ?? $i, $i + $this->updateOffset);
			}
		$tester->flush();
		$this->setResult('Update', $timer);

		$timer = new \ThePHPBench\BaseLine();

		for ($i = 1; $i <= $iterations; ++$i)
			{
			$expected = $i + $this->updateOffset;
			$tester->verifyUpdatedState($tester->read($this->insertedIds[$i] ?? $i), $expected);
			}
		$this->setResult('Update Test', $timer);

		$timer = new \ThePHPBench\BaseLine();

		echo "Executing random read test\n";

		foreach ($this->random as $i)
			{
			$expected = $i + $this->updateOffset;
			$tester->verifyUpdatedState($tester->read($this->insertedIds[$i] ?? $i), $expected);
			}
		$this->setResult('Random Read', $timer);

		$timer = new \ThePHPBench\BaseLine();

		echo "Executing delete test\n";

		for ($i = 1; $i <= $iterations; ++$i)
			{
			$tester->delete($this->insertedIds[$i] ?? $i);
			}
		$tester->flush();

		for ($i = 1; $i <= $iterations; ++$i)
			{
			if ($tester->read($this->insertedIds[$i] ?? $i))
				{
				throw new \Exception('Failed to delete ' . ($this->insertedIds[$i] ?? $i));
				}
			}
		$this->setResult('Delete', $timer);

		$this->setResult('Total Runtime', $runTime);

		return true;
		}
	}
