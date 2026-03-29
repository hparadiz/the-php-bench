<?php

namespace ThePHPBench\Cake;

class Tests extends \ThePHPBench\Test
	{
	private $connection;	// @phpstan-ignore-line

	/** @var array<int> */
	private array $deletes = [];

	protected ?\Cake\ORM\Table $activeTable = null;

	private string $primaryKey = 'string_variable_id';

	protected string $scenarioClass = 'StringVariable';

	private bool $transactionOpen = false;

	public function closeConnection() : void
		{
		if ($this->transactionOpen)
			{
			$this->connection->commit();
			$this->transactionOpen = false;
			}

		\Cake\ORM\TableRegistry::getTableLocator()->clear();
		$this->activeTable = null;
		$this->connection = null;
		}

	/**
	 * class must delete one record with id=$id
	 */
	public function delete(int $id) : bool
		{
		$this->deletes[] = $id;
		$this->beginTransaction();

		return true;
		}

	public function flush() : void
		{
		if ($this->deletes)
			{
			$this->getActiveTable()->deleteAll([$this->primaryKey . ' IN' => $this->deletes]);
			$this->deletes = [];
			}

		if ($this->transactionOpen)
			{
			$this->connection->commit();
			$this->transactionOpen = false;
			}
		}

	/**
	 * Initialize the orm
	 *
	 * @param array<string> $lines sql to import into schema
	 */
	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$driver = \ucfirst(\strtolower($config->getDriver()));

		if ('Pgsql' === $driver)
			{
			$driver = 'Postgres';
			}
		$driver = '\\Cake\\Database\\Driver\\' . $driver;

		$run = \ThePHPBench\Cake\RunManager::get();
		\Cake\Datasource\ConnectionManager::setConfig($run, [
			'className' => 'Cake\Database\Connection',
			'driver' => $driver,
			'persistent' => false,
			'host' => $config->getHost(),
			'port' => $config->getPort(),
			'username' => $config->getUser(),
			'password' => $config->getPassword(),
			'database' => $config->getDatabase(),
			'encoding' => 'pgsql' === $config->getDriver() ? 'utf8' : 'utf8mb4',
			'timezone' => 'UTC',
			'cacheMetadata' => false,
		]);

		$this->connection = \Cake\Datasource\ConnectionManager::get($run);
		$this->scenarioClass = $this->scenarioClassName($this->getScenarioFamily($config));
		$this->primaryKey = $this->primaryKeyField();
		$this->activeTable = $this->newActiveTable();
		$this->activeTable->setConnection($this->connection);
		$callback = [$this->connection, 'execute'];

		$this->loadSchema($lines, $callback, $runTimer);

		return $this;
		}

	/**
	 * class must insert one record with id=$id
	 *
	 * @return int $id inserted
	 */
	public function insert(int $id) : int
		{
		$this->beginTransaction();
		$employee = $this->getActiveTable()->newEmptyEntity();
		$employee->{$this->primaryKey} = $id;	// @phpstan-ignore-line
		$this->applyPayload($employee, $this->payloadForRow($id));
		$this->getActiveTable()->save($employee);

		return $employee->{$this->primaryKey};
		}

	/**
	 * class must read and return one record with id=$id or null on no matching record
	 */
	public function read(int $id) : ?object
		{
		try
			{
			$employee = $this->getActiveTable()->get($id);
			}
		catch (\Exception $e)
			{
			$employee = null;
			}

		return $employee;
		}

	/**
	 * class must update one record with id=$id to have $to in the data
	 */
	public function update(int $id, int $to) : bool
		{
		$this->beginTransaction();
		$employee = $this->getActiveTable()->get($id);
		$this->applyPayload($employee, $this->payloadForRow($to));
		$this->getActiveTable()->save($employee);

		return true;
		}

	protected function getActiveTable() : \Cake\ORM\Table
		{
		return $this->activeTable ??= $this->newActiveTable();
		}

	protected function newActiveTable() : \Cake\ORM\Table
		{
		$className = $this->getTableClassName();

		return new $className();
		}

	protected function getTableClassName() : string
		{
		return '\\ThePHPBench\\Cake\\Table\\' . $this->scenarioClass;
		}

	private function beginTransaction() : void
		{
		if (! $this->transactionOpen)
			{
			$this->connection->begin();
			$this->transactionOpen = true;
			}
		}
	}
