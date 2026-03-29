<?php

namespace ThePHPBench\ActiveRecord;

class Tests extends \ThePHPBench\Test
	{
	/** @var array<int> */
	private array $deletes = [];

	private ?\ActiveRecord\Connection $connection = null;

	private string $modelClass = \ThePHPBench\ActiveRecord\Model\StringVariable::class;

	private string $primaryKey = 'string_variable_id';

	private bool $transactionOpen = false;

	public function closeConnection() : void
		{
		if ($this->transactionOpen)
			{
			$this->connection?->commit();
			$this->transactionOpen = false;
			}
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
			$this->modelClass::where([$this->primaryKey => $this->deletes])->delete_all();
			$this->deletes = [];
			}

		if ($this->transactionOpen)
			{
			$this->connection?->commit();
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
		if ('sqlite' !== $config->getDriver())
			{
			$connectionString = $config->getDriver() . '://' . $config->getUser() . ':' . $config->getPassword() . '@' . $config->getHost() . ':' . $config->getPort() . '/' . $config->getDatabase();
			}
		else
			{
			$database = $config->getDatabase();

			if (':memory:' !== $database)
				{
				\fclose(\fopen($database, 'a'));
				$connectionString = 'sqlite://unix(' . $database . ')';
				}
			else
				{
				$connectionString = 'sqlite://:memory:';
				}
			}
		$cfg = \ActiveRecord\Config::instance();
		$cfg->set_connections([
			'test' => $connectionString,
		]);
		$cfg->set_default_connection('test');

		$this->connection = \ActiveRecord\ConnectionManager::get_connection('test');
		$this->modelClass = '\\ThePHPBench\\ActiveRecord\\Model\\' . $this->scenarioClassName($this->getScenarioFamily($config));
		$this->primaryKey = $this->primaryKeyField();
		$callback = [$this->connection, 'query'];

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
		$employee = new $this->modelClass();
		$this->applyPayload($employee, $this->payloadForRow($id));

		$employee->save();

		return (int)$employee->{$this->primaryKey};
		}

	/**
	 * class must read and return one record with id=$id or null on no matching record
	 */
	public function read(int $id) : ?object
		{
		try
			{
			$employee = $this->modelClass::find($id);
			}
		catch (\ActiveRecord\Exception\RecordNotFound $e)
			{
			return null;
			}

		return $employee;
		}

	/**
	 * class must update one record with id=$id to have $to in the data
	 */
	public function update(int $id, int $to) : bool
		{
		$this->beginTransaction();
		$employee = $this->modelClass::find($id);
		$this->applyPayload($employee, $this->payloadForRow($to));

		return $employee->save();
		}

	private function beginTransaction() : void
		{
		if (! $this->transactionOpen)
			{
			$this->connection?->transaction();
			$this->transactionOpen = true;
			}
		}
	}
