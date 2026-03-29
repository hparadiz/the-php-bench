<?php

namespace ThePHPBench\DivergenceV3;

use Divergence\IO\Database\Connections as DB;

class Tests extends \ThePHPBench\Test
	{
	private string $modelClass = \ThePHPBench\DivergenceV3\Model\StringVariable::class;

	private string $primaryKey = 'string_variable_id';

	public function closeConnection() : void
		{
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

		$currentType = $ref->getProperty('currentConnectionType');
		$currentType->setAccessible(true);
		$currentType->setValue(null, null);
		}

	public function dbSupported(\ThePHPBench\Configuration $config) : bool
		{
		return \in_array($config->getDriver(), ['mysql', 'pgsql', 'sqlite'], true);
		}

	public function delete(int $id) : bool
		{
		return $this->modelClass::delete((string)$id);
		}

	/**
	 * @param array<string> $lines
	 */
	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$app = new class(__DIR__) extends \Divergence\App {
			public function __construct($path) { \Divergence\App::$App = $this; }
		};
		$app->Config = ['environment' => 'production'];

		$ref = new \ReflectionClass(DB::class);

		$configProp = $ref->getProperty('Config');
		$configProp->setAccessible(true);

		$connectionConfig = 'sqlite' === $config->getDriver()
			? ['path' => $config->getDatabase()]
			: [
				'driver'   => $config->getDriver(),
				'host'     => $config->getHost(),
				'database' => $config->getDatabase(),
				'username' => $config->getUser(),
				'password' => $config->getPassword(),
				'port'     => $config->getPort(),
			];

		$configProp->setValue(null, ['benchmark' => $connectionConfig]);

		$currentProp = $ref->getProperty('currentConnection');
		$currentProp->setAccessible(true);
		$currentProp->setValue(null, 'benchmark');
		$this->modelClass = '\\ThePHPBench\\DivergenceV3\\Model\\' . $this->scenarioClassName($this->getScenarioFamily($config));
		$this->primaryKey = $this->primaryKeyField();

		$pdo = DB::getConnection('benchmark');

		$callback = [$pdo, 'exec'];
		$this->loadSchema($lines, $callback, $runTimer);

		return $this;
		}

	public function insert(int $id) : int
		{
		$record = new $this->modelClass();
		$this->applyPayload($record, $this->payloadForRow($id));

		$record->save();

		return (int)$record->{$this->primaryKey};
		}

	public function read(int $id) : ?object
		{
		return $this->modelClass::getByID($id);
		}

	public function update(int $id, int $to) : bool
		{
		$record = $this->modelClass::getByID($id);
		$this->applyPayload($record, $this->payloadForRow($to));
		$record->save();

		return true;
		}
	}
