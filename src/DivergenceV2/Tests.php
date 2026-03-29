<?php

namespace ThePHPBench\DivergenceV2;

use Divergence\IO\Database\MySQL as DB;

class Tests extends \ThePHPBench\Test
	{
	private string $modelClass = \ThePHPBench\DivergenceV2\Model\StringVariable::class;

	private string $primaryKey = 'string_variable_id';

	public function closeConnection() : void
		{
		// reset DivergenceV2 DB connection state
		$ref = new \ReflectionClass(DB::class);

		$connections = $ref->getProperty('Connections');
		$connections->setAccessible(true);
		$connections->setValue(null, []);

		$config = $ref->getProperty('Config');
		$config->setAccessible(true);
		$config->setValue(null, null);

		$current = $ref->getProperty('currentConnection');
		$current->setAccessible(true);
		$current->setValue(null, null);
		}

	public function dbSupported(\ThePHPBench\Configuration $config) : bool
		{
		// DivergenceV2 only supports MySQL/MariaDB (no SQLite)
		return $config->getDriver() === 'mysql';
		}

	/**
	 * class must delete one record with id=$id
	 */
	public function delete(int $id) : bool
		{
		return $this->modelClass::delete((string)$id);
		}

	/**
	 * Initialize the orm
	 *
	 * @param array<string> $lines sql to import into schema
	 */
	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
				// Bootstrap a minimal App instance so MySQL::startQueryLog doesn't crash
		$app = new class(__DIR__) extends \Divergence\App {
			public function __construct($path) { \Divergence\App::$App = $this; }
		};
		$app->Config = ['environment' => 'production'];

		// Inject connection config directly, bypassing App config file
		$ref = new \ReflectionClass(DB::class);

		$configProp = $ref->getProperty('Config');
		$configProp->setAccessible(true);
		$configProp->setValue(null, [
			'benchmark' => [
				'host'     => $config->getHost(),
				'database' => $config->getDatabase(),
				'username' => $config->getUser(),
				'password' => $config->getPassword(),
				'port'     => $config->getPort(),
			],
		]);

		$currentProp = $ref->getProperty('currentConnection');
		$currentProp->setAccessible(true);
		$currentProp->setValue(null, 'benchmark');

		// Force a connection so we have a PDO instance for schema loading
		$pdo = DB::getConnection('benchmark');
		$this->modelClass = '\\ThePHPBench\\DivergenceV2\\Model\\' . $this->scenarioClassName($this->getScenarioFamily($config));
		$this->primaryKey = $this->primaryKeyField();

		$callback = [$pdo, 'exec'];
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
		$record = new $this->modelClass();
		$this->applyPayload($record, $this->payloadForRow($id));

		$record->save();

		return (int)$record->{$this->primaryKey};
		}

	/**
	 * class must read and return one record with id=$id or null on no matching record
	 */
	public function read(int $id) : ?object
		{
		return $this->modelClass::getByID($id);
		}

	/**
	 * class must update one record with id=$id to have $to in the data
	 */
	public function update(int $id, int $to) : bool
		{
		$record = $this->modelClass::getByID($id);
		$this->applyPayload($record, $this->payloadForRow($to));
		$record->save();

		return true;
		}
	}
