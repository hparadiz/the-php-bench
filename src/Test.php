<?php

namespace ThePHPBench;

use ThePHPBench\Seed\DeterministicSeeder;
use ThePHPBench\Seed\FieldDefinition;
use ThePHPBench\Seed\DumpRepository;
use ThePHPBench\Seed\ScenarioDefinition;
use ThePHPBench\Seed\ScenarioRegistry;

/**
 * Abstract interface class for tests to implement
 */
abstract class Test
	{
	private ?ScenarioDefinition $activeScenario = null;

	/**
	 * Don't do work in the constructor
	 */
	final public function __construct()
		{
		}

	/**
	 * Close the database connection
	 */
	abstract public function closeConnection() : void;

	public function dbSupported(\ThePHPBench\Configuration $config) : bool
		{
		return true;
		}

	/**
	 * class must delete one record with id=$id
	 *
	 * @return true if deleted
	 */
	abstract public function delete(int $id) : bool;

	/**
	 * override to flush buffers between tests if needed
	 */
	public function flush() : void
		{
		}

	/**
	 * Get the lines in the schema to load into the database
	 *
	 * @return array<string> sql to insert into database
	 */
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
		$insertBatchSize = (int)($config->getRaw()['seed_insert_batch_size'] ?? 250);
		$repository = new DumpRepository();

		$repository->ensure($scenario, $config->getDriver(), \max(1, $insertBatchSize), false);

		return $repository->readLines($scenario->id, $config->getDriver(), false);
		}

	/**
	 * Initialize the orm
	 *
	 * @param array<string> $lines sql to import into schema
	 */
	abstract public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static;

	/**
	 * class must insert one record with id=$id
	 *
	 * @return int $id inserted
	 */
	abstract public function insert(int $id) : int;

	/**
	 * @param array<string> $lines of sql to import into schema
	 */
	public function loadSchema(array $lines, callable $callback, \ThePHPBench\BaseLine $runTimer) : static
		{
		$runTimer->pause();
		$sql = '';

		foreach ($lines as $line)
			{
			// Ignoring comments from the SQL script
			if (\str_starts_with((string)$line, '--') || '' == $line)
				{
				continue;
				}

			$sql .= $line;

			if (\str_ends_with(\trim((string)$line), ';'))
				{
				\call_user_func($callback, $sql);
				$sql = '';
				}
			} // end foreach

		$runTimer->resume();

		return $this;
		}

	/**
	 * class must read and return one record with id=$id or null on no matching record
	 */
	abstract public function read(int $id) : ?object;

	protected function getVerificationField() : string
		{
		return 'last_name';
		}

	protected function getInitialVerificationValue(int $id) : string
		{
		return "Last {$id}";
		}

	protected function getUpdatedVerificationValue(int $id) : string
		{
		return "Updated {$id}";
		}

	/**
	 * class must test one record with id=$id
	 */
	public function testUpdate(object $record, string $expected) : bool
		{
		return $this->verifyScenarioData($record, [$this->getVerificationField() => $expected]);
		}

	public function verifyInitialState(object $record, int $id) : bool
		{
		return $this->verifyScenarioData($record, $this->payloadForRow($id));
		}

	public function verifyUpdatedState(object $record, int $id) : bool
		{
		return $this->verifyScenarioData($record, $this->payloadForRow($id));
		}

	protected function getScenarioFamily(\ThePHPBench\Configuration $config) : string
		{
		return $this->resolveScenario($config)->modelFamily;
		}

	protected function applyPayload(object $record, array $payload) : void
		{
		$fields = [];

		foreach ($this->requireScenario()->fields as $field)
			{
			$fields[$field->name] = $field;
			}

		foreach ($payload as $field => $value)
			{
			$record->{$field} = isset($fields[$field]) ? $this->transformPayloadValue($fields[$field], $value) : $value;
			}
		}

	protected function payloadForRow(int $rowNumber) : array
		{
		$scenario = $this->requireScenario();
		$row = (new DeterministicSeeder())->generateRow($scenario, $rowNumber);
		unset($row[$scenario->primaryKey]);

		return $row;
		}

	protected function primaryKeyField() : string
		{
		return $this->requireScenario()->primaryKey;
		}

	protected function scenarioClassName(string $family) : string
		{
		return match ($family) {
			'simple' => 'Simple',
			'canary' => 'Canary',
			'int_fixed' => 'IntFixed',
			'float_fixed' => 'FloatFixed',
			'string_fixed' => 'StringFixed',
			'string_variable' => 'StringVariable',
			default => throw new \InvalidArgumentException("Unknown scenario family: {$family}"),
		};
		}

	protected function verifyScenarioData(object $record, array $expected) : bool
		{
		$scenario = $this->requireScenario();

		foreach ($scenario->fields as $field)
			{
			if ($field->name === $scenario->primaryKey || ! \array_key_exists($field->name, $expected))
				{
				continue;
				}

			$actual = $this->readRecordField($record, $field->name);
			$normalizedActual = $this->normalizeFieldValue($field, $actual);
			$normalizedExpected = $this->normalizeFieldValue($field, $expected[$field->name]);

			if (! $this->fieldValuesEquivalent($field, $normalizedActual, $normalizedExpected))
				{
				throw new \RuntimeException("Record verification failed for {$field->name}. Expected: {$normalizedExpected}, Actual: {$normalizedActual}");
				}
			}

		return true;
		}

	protected function readRecordField(object $record, string $field) : mixed
		{
		$getter = 'get' . \str_replace(' ', '', \ucwords(\str_replace('_', ' ', $field)));

		if (\method_exists($record, $getter))
			{
			return $record->{$getter}();
			}

		if (isset($record->{$field}) || \property_exists($record, $field))
			{
			return $record->{$field};
			}

		return null;
		}

	protected function resolveScenario(\ThePHPBench\Configuration $config) : ScenarioDefinition
		{
		$this->activeScenario = $config->getScenario();

		return $this->activeScenario;
		}

	protected function requireScenario() : ScenarioDefinition
		{
		return $this->activeScenario ?? throw new \RuntimeException('Benchmark scenario has not been initialized');
		}

	private function normalizeFieldValue(FieldDefinition $field, mixed $value) : string|int|float|bool|null
		{
		if (\in_array($field->type, ['date', 'datetime'], true) && null !== $value)
			{
			$value = $this->normalizeTemporalInput($field->type, $value);
			}

		if ($value instanceof \DateTimeInterface)
			{
			$value = match ($field->type) {
				'date' => $value->format('Y-m-d'),
				'datetime' => $value->format('Y-m-d H:i:s'),
				default => $value->format(\DATE_ATOM),
			};
			}

		if (null === $value)
			{
			return null;
			}

		return match ($field->type) {
			'id', 'smallint', 'integer', 'bigint' => (int)$value,
			'boolean' => (bool)$value,
			'float', 'double' => (float)$value,
			'decimal' => \is_string($value) ? $value : \number_format((float)$value, $field->scale ?? 0, '.', ''),
			'char' => \rtrim((string)$value),
			'varchar', 'text' => \rtrim((string)$value),
			default => (string)$value,
		};
		}

	protected function transformPayloadValue(FieldDefinition $field, mixed $value) : mixed
		{
		return $value;
		}

	private function fieldValuesEquivalent(FieldDefinition $field, string|int|float|bool|null $actual, string|int|float|bool|null $expected) : bool
		{
		if ($actual === $expected)
			{
			return true;
			}

		return match ($field->type) {
			'float', 'double' => \abs((float)$actual - (float)$expected) <= \max(0.1, \abs((float)$expected) * 0.00001),
			'decimal' => \abs((float)$actual - (float)$expected) <= \max(0.000001, \abs((float)$expected) * 0.0000001),
			default => false,
		};
		}

	private function normalizeTemporalInput(string $type, mixed $value) : string
		{
		if ($value instanceof \DateTimeInterface)
			{
			return 'date' === $type ? $value->format('Y-m-d') : $value->format('Y-m-d H:i:s');
			}

		if (\is_numeric($value))
			{
			$timestamp = (new \DateTimeImmutable('@' . (string)(int)$value))->setTimezone(new \DateTimeZone(date_default_timezone_get()));

			return 'date' === $type ? $timestamp->format('Y-m-d') : $timestamp->format('Y-m-d H:i:s');
			}

		try
			{
			$parsed = new \DateTimeImmutable((string)$value);

			return 'date' === $type ? $parsed->format('Y-m-d') : $parsed->format('Y-m-d H:i:s');
			}
		catch (\Exception)
			{
			return (string)$value;
			}
		}

	/**
	 * class must update one record with id=$id to have $to in the data
	 */
	abstract public function update(int $id, int $to) : bool;
	}
