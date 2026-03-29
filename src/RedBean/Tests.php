<?php

declare(strict_types=1);

namespace ThePHPBench\RedBean;

use ThePHPBench\Seed\FieldDefinition;
use ThePHPBench\Seed\ScenarioDefinition;
use ThePHPBench\Seed\ScenarioRegistry;
use ThePHPBench\Seed\SqlEmitter;

class Tests extends \ThePHPBench\Test
	{
	private string $beanType = 'stringvariablerecord';

	public function closeConnection() : void
		{
		\RedBeanPHP\R::close();
		\RedBeanPHP\R::removeToolBoxByKey('default');
		}

	public function delete(int $id) : bool
		{
		$record = $this->read($id);

		return $record ? 1 === \RedBeanPHP\R::trash($record) : false;
		}

	public function getSchemaLines(\ThePHPBench\Configuration $config) : array
		{
		$connection = $config->getPDOConnectionString();

		if (\str_contains($connection, 'sqlite') && ! \str_contains($connection, 'memory'))
			{
			$database = $config->getDatabase();
			$directory = \dirname($database);

			if (! \is_dir($directory))
				{
				\mkdir($directory, 0777, true);
				}

			if (! \file_exists($database))
				{
				\fclose(\fopen($database, 'w'));
				}
			}

		$scenario = $this->resolveScenario($config);
		$this->beanType = \strtolower($this->scenarioClassName($scenario->modelFamily)) . 'record';
		$fields = [new FieldDefinition('id', 'id')];

		foreach ($scenario->fields as $field)
			{
			if ($field->name !== $scenario->primaryKey)
				{
				$fields[] = $field;
				}
			}

		$redBeanScenario = new ScenarioDefinition(
			$scenario->id,
			$scenario->modelFamily,
			$this->beanType,
			'id',
			$scenario->rowCount,
			$scenario->indexed,
			$fields,
			$scenario->indexedColumns,
		);

		return \array_map(
			static fn(string $statement) : string => $statement . "\n",
			(new SqlEmitter())->emit($redBeanScenario, $config->getDriver(), [])
		);
		}

	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		\RedBeanPHP\R::setup($config->getPDOConnectionString(), $config->getUser(), $config->getPassword());
		$this->loadSchema($lines, [\RedBeanPHP\Facade::class, 'exec'], $runTimer);

		return $this;
		}

	public function insert(int $id) : int
		{
		$record = \RedBeanPHP\R::dispense($this->beanType);
		$this->applyPayload($record, $this->payloadForRow($id));

		return (int)\RedBeanPHP\R::store($record);
		}

	public function read(int $id) : ?object
		{
		$record = \RedBeanPHP\R::load($this->beanType, $id);

		return $record->id ? $record : null;
		}

	public function update(int $id, int $to) : bool
		{
		$record = $this->read($id);

		if (! $record)
			{
			return false;
			}

		$this->applyPayload($record, $this->payloadForRow($to));

		return 0 !== \RedBeanPHP\R::store($record);
		}
	}
