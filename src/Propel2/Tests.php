<?php

declare(strict_types=1);

namespace ThePHPBench\Propel2;

class Tests extends \ThePHPBench\Test
	{
	private ?\Propel\Runtime\Connection\ConnectionInterface $connection = null;

	private ?\Propel\Runtime\Connection\ConnectionManagerSingle $manager = null;

	private bool $simpleScenario = false;

	private string $scenarioClass = 'StringVariable';

	public function closeConnection() : void
		{
		$this->manager?->closeConnections();
		$this->connection = null;
		$this->manager = null;
		}

	public function delete(int $id) : bool
		{
		if ($this->simpleScenario)
			{
			return false !== $this->connection?->exec('DELETE FROM simple_records WHERE simple_id = ' . (int)$id);
			}

		$queryClass = '\\ThePHPBench\\Propel2\\' . $this->scenarioClass . 'Query';
		$record = $queryClass::create()->findPk($id);

		if (! $record)
			{
			return false;
			}

		$record->delete();

		return true;
		}

	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$this->simpleScenario = 'simple' === $this->getScenarioFamily($config);
		$this->scenarioClass = $this->scenarioClassName($this->getScenarioFamily($config));
		$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
		$serviceContainer->checkVersion(2);
		$serviceContainer->setAdapterClass('default', $config->getDriver());

		$this->manager = new \Propel\Runtime\Connection\ConnectionManagerSingle('default');
		$this->manager->setConfiguration([
			'dsn' => $config->getPDOConnectionString(),
			'user' => $config->getUser(),
			'password' => $config->getPassword(),
			'settings' => ['charset' => 'utf8', 'queries' => []],
			'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
			'model_paths' => [0 => 'src', 1 => 'vendor'],
		]);

		$serviceContainer->setConnectionManager($this->manager);
		$serviceContainer->setDefaultDatasource('default');
		$serviceContainer->initDatabaseMapFromDumps([
			'default' => [
				'tablesByName' => [
					'canaries' => '\\ThePHPBench\\Propel2\\Map\\CanaryTableMap',
					'int_fixed_records' => '\\ThePHPBench\\Propel2\\Map\\IntFixedTableMap',
					'float_fixed_records' => '\\ThePHPBench\\Propel2\\Map\\FloatFixedTableMap',
					'string_fixed_records' => '\\ThePHPBench\\Propel2\\Map\\StringFixedTableMap',
					'string_variable_records' => '\\ThePHPBench\\Propel2\\Map\\StringVariableTableMap',
				],
				'tablesByPhpName' => [
					'\\Canary' => '\\ThePHPBench\\Propel2\\Map\\CanaryTableMap',
					'\\IntFixed' => '\\ThePHPBench\\Propel2\\Map\\IntFixedTableMap',
					'\\FloatFixed' => '\\ThePHPBench\\Propel2\\Map\\FloatFixedTableMap',
					'\\StringFixed' => '\\ThePHPBench\\Propel2\\Map\\StringFixedTableMap',
					'\\StringVariable' => '\\ThePHPBench\\Propel2\\Map\\StringVariableTableMap',
				],
			],
		]);

		$this->connection = $serviceContainer->getWriteConnection('default');
		$this->loadSchema($lines, [$this, 'runSQL'], $runTimer);

		return $this;
		}

	public function insert(int $id) : int
		{
		if ($this->simpleScenario)
			{
			$stmt = $this->connection?->prepare('INSERT INTO simple_records (simple_id, title) VALUES (:id, :title)');
			$stmt?->bindValue(':id', $id, \PDO::PARAM_INT);
			$stmt?->bindValue(':title', "Title {$id}", \PDO::PARAM_STR);
			$stmt?->execute();

			return $id;
			}

		$class = '\\ThePHPBench\\Propel2\\' . $this->scenarioClass;
		$record = new $class();
		$setter = 'set' . \str_replace(' ', '', \ucwords(\str_replace('_', ' ', $this->primaryKeyField())));
		$record->{$setter}($id);
		$this->applyPropelPayload($record, $this->payloadForRow($id));
		$record->save();

		$getter = 'get' . \str_replace(' ', '', \ucwords(\str_replace('_', ' ', $this->primaryKeyField())));

		return (int)$record->{$getter}();
		}

	public function read(int $id) : ?object
		{
		if ($this->simpleScenario)
			{
			$stmt = $this->connection?->prepare('SELECT simple_id, title FROM simple_records WHERE simple_id = :id');
			$stmt?->bindValue(':id', $id, \PDO::PARAM_INT);
			$stmt?->execute();
			$row = $stmt?->fetch(\PDO::FETCH_ASSOC);

			return $row ? (object)$row : null;
			}

		$queryClass = '\\ThePHPBench\\Propel2\\' . $this->scenarioClass . 'Query';

		return $queryClass::create()->findPk($id);
		}

	public function runSQL(string $sql) : void
		{
		if ($sql)
			{
			$this->connection?->exec($sql);
			}
		}

	public function testUpdate(object $record, string $expected) : bool
		{
		if ($this->simpleScenario)
			{
			if (($record->title ?? null) !== $expected)
				{
				throw new \RuntimeException("Record update failed for title. Expected: {$expected}, Actual: " . ($record->title ?? 'null'));
				}

			return true;
			}

		return parent::testUpdate($record, $expected);
		}

	public function update(int $id, int $to) : bool
		{
		if ($this->simpleScenario)
			{
			$title = $this->payloadForRow($to)['title'] ?? "Title {$to}";
			$stmt = $this->connection?->prepare('UPDATE simple_records SET title = :title WHERE simple_id = :id');
			$stmt?->bindValue(':title', $title, \PDO::PARAM_STR);
			$stmt?->bindValue(':id', $id, \PDO::PARAM_INT);

			return (bool)$stmt?->execute();
			}

		$queryClass = '\\ThePHPBench\\Propel2\\' . $this->scenarioClass . 'Query';
		$record = $queryClass::create()->findPk($id);

		if (! $record)
			{
			return false;
			}

		$this->applyPropelPayload($record, $this->payloadForRow($to));

		return 0 !== $record->save();
		}

	private function applyPropelPayload(object $record, array $payload) : void
		{
		foreach ($payload as $field => $value)
			{
			$setter = 'set' . \str_replace(' ', '', \ucwords(\str_replace('_', ' ', $field)));
			$record->{$setter}($value);
			}
		}
	}
