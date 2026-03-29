<?php

declare(strict_types=1);

namespace ThePHPBench\Cycle;

use Cycle\Database\Config;
use Cycle\Database\DatabaseManager;

final class Tests extends \ThePHPBench\Test
	{
	private ?DatabaseManager $dbal = null;

	private string $primaryKey = 'string_variable_id';

	private string $table = 'string_variable';

	public function closeConnection() : void
		{
		$this->dbal = null;
		}

	public function delete(int $id) : bool
		{
		return 0 < $this->database()->execute(
			'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = ?',
			[$id]
		);
		}

	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$this->dbal = new DatabaseManager($this->databaseConfig($config));
		$this->table = $this->resolveScenario($config)->tableName;
		$this->primaryKey = $this->primaryKeyField();
		$this->loadSchema($lines, [$this->database(), 'execute'], $runTimer);

		return $this;
		}

	public function insert(int $id) : int
		{
		$payload = [$this->primaryKey => $id] + $this->payloadForRow($id);
		$columns = \array_keys($payload);
		$params = \array_values($payload);
		$placeholders = \implode(', ', \array_fill(0, \count($columns), '?'));

		$this->database()->execute(
			'INSERT INTO ' . $this->table
			. ' (' . \implode(', ', $columns) . ') VALUES (' . $placeholders . ')',
			$params
		);

		return $id;
		}

	public function read(int $id) : ?object
		{
		$rows = $this->database()->query(
			'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = ?',
			[$id]
		)->fetchAll();

		return isset($rows[0]) && \is_array($rows[0]) ? (object)$rows[0] : null;
		}

	public function update(int $id, int $to) : bool
		{
		$payload = $this->payloadForRow($to);
		$assignments = [];
		$params = [];

		foreach ($payload as $column => $value)
			{
			$assignments[] = $column . ' = ?';
			$params[] = $value;
			}

		$params[] = $id;

		return 0 < $this->database()->execute(
			'UPDATE ' . $this->table . ' SET ' . \implode(', ', $assignments)
			. ' WHERE ' . $this->primaryKey . ' = ?',
			$params
		);
		}

	private function database() : \Cycle\Database\DatabaseInterface
		{
		return $this->dbal?->database('default') ?? throw new \RuntimeException('Cycle DBAL not initialized');
		}

	private function databaseConfig(\ThePHPBench\Configuration $config) : Config\DatabaseConfig
		{
		$driver = $config->getDriver();
		$connectionName = 'runtime';
		$connection = match ($driver) {
			'sqlite' => ':memory:' === $config->getDatabase()
				? new Config\SQLiteDriverConfig(connection: new Config\SQLite\MemoryConnectionConfig(), queryCache: true)
				: new Config\SQLiteDriverConfig(
					connection: new Config\SQLite\FileConnectionConfig(database: $config->getDatabase()),
					queryCache: true
				),
			'mysql' => new Config\MySQLDriverConfig(
				connection: new Config\MySQL\TcpConnectionConfig(
					database: $config->getDatabase(),
					host: $config->getHost(),
					port: $config->getPort(),
					user: $config->getUser(),
					password: $config->getPassword(),
				),
				queryCache: true
			),
			'pgsql' => new Config\PostgresDriverConfig(
				connection: new Config\Postgres\TcpConnectionConfig(
					database: $config->getDatabase(),
					host: $config->getHost(),
					port: $config->getPort(),
					user: $config->getUser(),
					password: $config->getPassword(),
				),
				schema: 'public',
				queryCache: true
			),
			default => throw new \RuntimeException("Unsupported Cycle driver: {$driver}"),
		};

		return new Config\DatabaseConfig([
			'default' => 'default',
			'databases' => [
				'default' => [
					'connection' => $connectionName,
				],
			],
			'connections' => [
				$connectionName => $connection,
			],
		]);
		}
	}
