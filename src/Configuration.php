<?php

namespace ThePHPBench;

use ThePHPBench\Seed\ScenarioDefinition;
use ThePHPBench\Seed\ScenarioRegistry;

class Configuration
	{
	/**
	 * @param array<string,mixed> $config
	 */
	public function __construct(private array $config, private int $iterations)
		{
		$this->iterations = (int)($config['iterations'] ?? $iterations);
		}

	public function getDatabase() : string
		{
		if (! empty($this->config['dbname']))
			{
			return 'sqlite' === $this->getDriver()
				? $this->normalizeSqliteDatabase((string)$this->config['dbname'])
				: (string)$this->config['dbname'];
			}

		$database = \strtolower($this->getNamespace());

		if ('sqlite' == $this->getDriver())
			{
			return $this->normalizeSqliteDatabase($database);
			}

		return $database;
		}

	public function getDescription() : string
		{
		return $this->config['description'] ?? $this->getPDOConnectionString();
		}

	public function getDriver() : string
		{
		return $this->config['driver'] ?? 'sqlite';
		}

	public function getHost() : string
		{
		return $this->config['host'] ?? 'localhost';
		}

	public function getIterations() : int
		{
		return $this->iterations;
		}

	public function getNamespace() : string
		{
		return $this->config['namespace'] ?? 'PHPFUI';
		}

	public function getPassword() : string
		{
		return $this->config['password'] ?? '';
		}

	public function getPDOConnectionString() : string
		{
		$driver = $this->getDriver();

		if ('sqlite' == $driver)
			{
			$driver .= ':' . $this->getDatabase();
			}
		else
			{
			$driver .= ':host=' . $this->getHost() . ';dbname=' . $this->getDatabase() . ';port=' . $this->getPort();
			}

		if ('pgsql' == $driver)
			{
			$driver .= ':user=' . $this->getUser() . ';password=' . $this->getPassword();
			}

		return $driver;
		}

	public function getPort() : int
		{
		return $this->config['port'] ?? 3306;
		}

	public function getUser() : string
		{
		return $this->config['user'] ?? 'root';
		}

	/** @return array<string,string|int> */
	public function getRaw() : array
		{
		return $this->config;
		}

	public function getPriority() : int
		{
		return (int)($this->config['priority'] ?? 100);
		}

	public function getScenario() : ScenarioDefinition
		{
		return ScenarioRegistry::get($this->getScenarioId());
		}

	public function getScenarioId() : string
		{
		return (string)($this->config['scenario'] ?? 'string_variable.500.indexed');
		}

	private function normalizeSqliteDatabase(string $database) : string
		{
		if (':memory:' === $database)
			{
			return $database;
			}

		if (! \str_ends_with($database, '.sqlite'))
			{
			$database .= '.sqlite';
			}

		if ($this->isAbsolutePath($database))
			{
			return $database;
			}

		$root = \dirname(__DIR__);
		$storageRoot = $root . '/storage/sqlite';

		if (\str_contains($database, '/'))
			{
			return $root . '/' . \ltrim($database, '/');
			}

		return $storageRoot . '/' . $database;
		}

	private function isAbsolutePath(string $path) : bool
		{
		return \str_starts_with($path, '/') || (bool)\preg_match('/^[A-Za-z]:[\\\\\\/]/', $path);
		}
	}
