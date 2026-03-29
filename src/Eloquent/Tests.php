<?php

namespace ThePHPBench\Eloquent;

class Tests extends \ThePHPBench\Test
	{
	protected \Illuminate\Database\Capsule\Manager $capsule;

	/** @var array<int> */
	private array $deletes = [];

	protected ?\PDO $pdo;

	private string $modelClass = \ThePHPBench\Eloquent\Model\StringVariable::class;

	private string $primaryKey = 'string_variable_id';

	private bool $transactionOpen = false;

	public function closeConnection() : void
		{
		if ($this->transactionOpen)
			{
			$this->capsule->getConnection()->commit();
			$this->transactionOpen = false;
			}

		$this->capsule->getDatabaseManager()->disconnect('default');
		\Illuminate\Database\Eloquent\Model::clearBootedModels();
		\Illuminate\Database\Eloquent\Model::unsetConnectionResolver();
		$this->pdo = null;
		}

	/**
	 * class must delete one record with id=$id
	 */
	public function delete(int $id) : bool
		{
		return 0 !== $this->modelClass::destroy($id);
		}

	public function flush() : void
		{
		if ($this->deletes)
			{
			$this->modelClass::destroy($this->deletes);
			$this->deletes = [];
			}

		if ($this->transactionOpen)
			{
			$this->capsule->getConnection()->commit();
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
		$this->capsule = new \Illuminate\Database\Capsule\Manager();

		$database = $config->getDatabase();

		if ($config->getDriver() === 'sqlite' && $database !== ':memory:')
			{
			$database = \realpath(\dirname($database)) . \DIRECTORY_SEPARATOR . \basename($database);

			if (! \file_exists($database))
				{
				\fclose(\fopen($database, 'w'));
				}
			}

		$this->capsule->addConnection([
			'driver' => $config->getDriver(),
			'host' => $config->getHost(),
			'database' => $database,
			'username' => $config->getUser(),
			'password' => $config->getPassword(),
			'charset' => 'utf8',
			'port' => $config->getPort(),
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
		]);

		$this->pdo = $this->capsule->getConnection()->getPdo();

		// Set the event dispatcher used by Eloquent models... (optional)
		$this->capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container()));

		// Make this Capsule instance available globally via static methods... (optional)
		$this->capsule->setAsGlobal();

		// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
		$this->capsule->bootEloquent();
		$this->modelClass = '\\ThePHPBench\\Eloquent\\Model\\' . $this->scenarioClassName($this->getScenarioFamily($config));
		$this->primaryKey = $this->primaryKeyField();

		$callback = [$this->pdo, 'exec'];

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
		return $this->modelClass::find($id);	// @phpstan-ignore-line
		}

	/**
	 * class must update one record with id=$id to have $to in the data
	 */
	public function update(int $id, int $to) : bool
		{
		$employee = $this->read($id);
		$this->applyPayload($employee, $this->payloadForRow($to));

		return $employee->update();
		}
	}
