<?php

namespace ThePHPBench\PHPFUI;

use ThePHPBench\Seed\FieldDefinition;

class Tests extends \ThePHPBench\Test
	{
	private ?\PHPFUI\ORM\PDOInstance $pdo;

	private string $recordClass = \ThePHPBench\PHPFUI\Record\StringVariable::class;

	private string $tableClass = \ThePHPBench\PHPFUI\Table\StringVariable::class;

	private string $primaryKey = 'string_variable_id';

	public function closeConnection() : void
		{
		$this->pdo = null;
		}

	/**
	 * class must delete one record with id=$id
	 */
	public function delete(int $id) : bool
		{
		$recordClass = $this->getRecordClassName();
		$employee = new $recordClass();
		$employee->{$this->getPrimaryKeyField()} = $id;

		return $employee->delete();	// @phpstan-ignore-line
		}

	/**
	 * Initialize the orm
	 *
	 * @param array<string> $lines sql to import into schema
	 */
	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$connection = $config->getPDOConnectionString();
		$this->pdo = new \PHPFUI\ORM\PDOInstance($connection, $config->getUser(), $config->getPassword());
		\PHPFUI\ORM::addConnection($this->pdo);
		\PHPFUI\ORM::$namespaceRoot = __DIR__ . '/../..';
		\PHPFUI\ORM::$recordNamespace = 'ThePHPBench\PHPFUI\Record';
		\PHPFUI\ORM::$tableNamespace = 'ThePHPBench\PHPFUI\Table';
		\PHPFUI\ORM::$migrationNamespace = 'ThePHPBench\PHPFUI\Migration';
		\PHPFUI\ORM::$idSuffix = '_id';
		$className = $this->scenarioClassName($this->getScenarioFamily($config));
		$this->recordClass = '\\ThePHPBench\\PHPFUI\\Record\\' . $className;
		$this->tableClass = '\\ThePHPBench\\PHPFUI\\Table\\' . $className;
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
		$recordClass = $this->getRecordClassName();
		$employee = new $recordClass();
		$employee->{$this->getPrimaryKeyField()} = $id;
		$this->applyPayload($employee, $this->payloadForRow($id));

		$employee->insert();

		return $id;
		}

	/**
	 * class must read and return one record with id=$id or null on no matching record
	 */
	public function read(int $id) : ?object
		{
		$recordClass = $this->getRecordClassName();
		$employee = new $recordClass($id);

		if (! $employee->loaded())
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
		$recordClass = $this->getRecordClassName();
		$employee = new $recordClass($id);
		$this->applyPayload($employee, $this->payloadForRow($to));

		return $employee->update();
		}

	protected function getPrimaryKeyField() : string
		{
		return $this->primaryKey;
		}

	protected function getRecordClassName() : string
		{
		return $this->recordClass;
		}

	protected function getTableClassName() : string
		{
		return $this->tableClass;
		}

	protected function transformPayloadValue(FieldDefinition $field, mixed $value) : mixed
		{
		if ('boolean' === $field->type)
			{
			return $value ? 1 : 0;
			}

		return $value;
		}
	}
