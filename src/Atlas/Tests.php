<?php

declare(strict_types=1);

namespace ThePHPBench\Atlas;

final class Tests extends \ThePHPBench\Test
	{
	private ?\Atlas\Pdo\Connection $connection = null;

	private string $primaryKey = 'string_variable_id';

	private string $table = 'string_variable';

	public function closeConnection() : void
		{
		$this->connection = null;
		}

	public function delete(int $id) : bool
		{
		return 0 < $this->connection?->fetchAffected(
			'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id',
			['id' => $id]
		);
		}

	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$this->connection = \Atlas\Pdo\Connection::new(
			$config->getPDOConnectionString(),
			$config->getUser(),
			$config->getPassword(),
		);
		$this->table = $this->resolveScenario($config)->tableName;
		$this->primaryKey = $this->primaryKeyField();
		$this->loadSchema($lines, [$this->connection, 'fetchAffected'], $runTimer);

		return $this;
		}

	public function insert(int $id) : int
		{
		$payload = [$this->primaryKey => $id] + $this->payloadForRow($id);
		$columns = \array_keys($payload);
		$bind = [];

		foreach ($payload as $column => $value)
			{
			$bind[$column] = $value;
			}

		$this->connection?->fetchAffected(
			'INSERT INTO ' . $this->table
			. ' (' . \implode(', ', $columns) . ') VALUES (:'
			. \implode(', :', $columns) . ')',
			$bind
		);

		return $id;
		}

	public function read(int $id) : ?object
		{
		$rows = $this->connection?->fetchAll(
			'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id',
			['id' => $id]
		) ?? [];

		return isset($rows[0]) && \is_array($rows[0]) ? (object)$rows[0] : null;
		}

	public function update(int $id, int $to) : bool
		{
		$payload = $this->payloadForRow($to);
		$assignments = [];
		$bind = ['id' => $id];

		foreach ($payload as $column => $value)
			{
			$assignments[] = $column . ' = :' . $column;
			$bind[$column] = $value;
			}

		return 0 < $this->connection?->fetchAffected(
			'UPDATE ' . $this->table . ' SET ' . \implode(', ', $assignments)
			. ' WHERE ' . $this->primaryKey . ' = :id',
			$bind
		);
		}
	}
