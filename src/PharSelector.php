<?php

declare(strict_types=1);

namespace ThePHPBench;

use ThePHPBench\Seed\ScenarioRegistry;

final class PharSelector
	{
	/**
	 * @param list<string> $filters
	 */
	public static function runtimePayload(string $framework, array $filters = []) : string
		{
		$match = self::selectRuntimeTest($framework, $filters);

		return \base64_encode((string)\json_encode([
			'test' => $match,
			'iterations' => (int)($match['iterations'] ?? 10),
			'runNumber' => 1,
			'totalRuns' => 1,
		]));
		}

	/**
	 * @param list<string> $filters
	 * @return array<string, mixed>
	 */
	private static function selectRuntimeTest(string $framework, array $filters) : array
		{
		$matches = [];

		foreach (self::candidateTests() as $test)
			{
			if (($test['namespace'] ?? null) !== $framework)
				{
				continue;
				}

			if (! self::matchesFilters($test, $filters))
				{
				continue;
				}

			$matches[] = $test;
			}

		if ($matches === [])
			{
			throw new \RuntimeException("No matching benchmark found for {$framework}");
			}

		if (\count($matches) > 1)
			{
			$descriptions = \array_map(
				static fn(array $test) : string => (string)($test['description'] ?? (($test['scenario'] ?? 'unknown') . ' ' . ($test['driver'] ?? 'unknown'))),
				\array_slice($matches, 0, 8)
			);

			throw new \RuntimeException(
				"Ambiguous benchmark selector for {$framework}: " . \implode(', ', $descriptions)
			);
			}

		return $matches[0];
		}

	/**
	 * @return list<array<string, mixed>>
	 */
	private static function candidateTests() : array
		{
		self::loadSeedClasses();

		$sharedSecrets = require \dirname(__DIR__) . '/config/load-secrets.php';
		$benchmarkMySql = \is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : [];
		$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null) ? $sharedSecrets['benchmark_mariadb'] : $benchmarkMySql;
		$benchmarkPostgres = \is_array($sharedSecrets['benchmark_postgres'] ?? null) ? $sharedSecrets['benchmark_postgres'] : [];
		$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;
		$tests = [];

		$fullFamilies = self::scenarioDefinitions();

		foreach (['ActiveRecord'] as $namespace)
			{
			$tests[] = ['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0];
			$tests[] = ['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-activerecord.sqlite', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0];
			$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'Simple MySQL', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkMySql);
			$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkPostgres);
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'sqlite', 'sqlite::memory:', ':memory:', $fullFamilies)];
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'sqlite', 'sqlite::file:', 'activerecord.sqlite', $fullFamilies)];
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'pgsql', 'PostgreSQL', null, $fullFamilies, $benchmarkPostgres)];
			}

		foreach (['Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV3'] as $namespace)
			{
			$slug = \strtolower($namespace);
			$tests[] = ['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0];
			$tests[] = ['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => "simple-{$slug}.sqlite", 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0];
			$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'Simple MySQL', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkMySql);
			$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307, 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkMariaDb);
			if ($namespace !== 'DivergenceV3')
				{
				$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkPostgres);
				}
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'sqlite', 'sqlite::memory:', ':memory:', $fullFamilies)];
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'sqlite', 'sqlite::file:', "{$slug}.sqlite", $fullFamilies)];
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'mysql', 'MariaDB', null, $fullFamilies, $benchmarkMariaDb)];
			if ($namespace !== 'DivergenceV3')
				{
				$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'pgsql', 'PostgreSQL', null, $fullFamilies, $benchmarkPostgres)];
				}
			}

		foreach (['DivergenceV2'] as $namespace)
			{
			$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'Simple MySQL', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkMySql);
			$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307, 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $benchmarkMariaDb);
			$tests = [...$tests, ...self::fullScenarioMatrix($namespace, 'mysql', 'MariaDB', null, $fullFamilies, $benchmarkMariaDb)];
			}

		return $tests;
		}

	private static function loadSeedClasses() : void
		{
		require_once __DIR__ . '/Seed/FieldDefinition.php';
		require_once __DIR__ . '/Seed/ScenarioDefinition.php';
		require_once __DIR__ . '/Seed/ScenarioRegistry.php';
		}

	/**
	 * @return list<array{id: string, family: string, row_count: int, label: string}>
	 */
	private static function scenarioDefinitions() : array
		{
		$definitions = [];

		foreach (ScenarioRegistry::all() as $scenario)
			{
			if (! $scenario->indexed || $scenario->modelFamily === 'simple')
				{
				continue;
				}

			$definitions[] = [
				'id' => $scenario->id,
				'family' => $scenario->modelFamily,
				'row_count' => $scenario->rowCount,
				'label' => self::familyLabel($scenario->modelFamily) . ' ' . $scenario->rowCount,
			];
			}

		\usort($definitions, static function(array $a, array $b) : int
			{
			$familyOrder = self::familyOrder($a['family']) <=> self::familyOrder($b['family']);

			return $familyOrder !== 0 ? $familyOrder : ($a['row_count'] <=> $b['row_count']);
			});

		return $definitions;
		}

	private static function familyLabel(string $family) : string
		{
		return match ($family) {
			'canary' => 'Canary',
			'float_fixed' => 'FloatFixed',
			'int_fixed' => 'IntFixed',
			'string_fixed' => 'StringFixed',
			'string_variable' => 'StringVariable',
			'simple' => 'Simple',
			default => $family,
		};
		}

	private static function familyOrder(string $family) : int
		{
		return match ($family) {
			'canary' => 10,
			'float_fixed' => 20,
			'int_fixed' => 30,
			'string_fixed' => 40,
			'string_variable' => 50,
			'simple' => 0,
			default => 999,
		};
		}

	/**
	 * @param list<array{id: string, family: string, row_count: int, label: string}> $definitions
	 * @param array<string, mixed> $secrets
	 * @return list<array<string, mixed>>
	 */
	private static function fullScenarioMatrix(string $namespace, string $driver, string $description, ?string $dbname, array $definitions, array $secrets = []) : array
		{
		$tests = [];
		$priority = 100;

		foreach ($definitions as $definition)
			{
			$test = [
				'namespace' => $namespace,
				'driver' => $driver,
				'description' => $definition['label'] . ' ' . $description,
				'scenario' => $definition['id'],
				'iterations' => $definition['row_count'],
				'priority' => $priority++,
			];

			if ($dbname !== null)
				{
				$test['dbname'] = $dbname;
				}

			$tests[] = $secrets ? \array_replace($test, $secrets) : $test;
			}

		return $tests;
		}

	/**
	 * @param array<string, mixed> $test
	 * @param list<string> $filters
	 */
	private static function matchesFilters(array $test, array $filters) : bool
		{
		if ($filters === [])
			{
			return (string)($test['scenario'] ?? '') === 'simple.10.indexed';
			}

		$needles = [];
		$needles[] = (string)($test['namespace'] ?? '');
		$needles[] = (string)($test['description'] ?? '');
		$needles[] = (string)($test['scenario'] ?? '');
		$needles[] = (string)($test['driver'] ?? '');

		if (\preg_match('/^([a-z_]+)\.(\d+)\./i', (string)($test['scenario'] ?? ''), $matches))
			{
			$needles[] = $matches[1];
			$needles[] = $matches[1] . ' ' . $matches[2];
			$needles[] = $matches[2];
			}

		$haystack = \strtolower(\implode(' ', \array_filter($needles, static fn(string $value) : bool => $value !== '')));

		foreach ($filters as $filter)
			{
			$filter = \strtolower(\trim($filter));

			if ($filter === '')
				{
				continue;
				}

			if (! \str_contains($haystack, $filter))
				{
				return false;
				}
			}

		return true;
		}
	}
