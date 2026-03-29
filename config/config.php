<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';
require_once __DIR__ . '/build-matrix.php';

$benchmarkMySql = \is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : [];
$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null) ? $sharedSecrets['benchmark_mariadb'] : $benchmarkMySql;
$benchmarkPostgres = \is_array($sharedSecrets['benchmark_postgres'] ?? null) ? $sharedSecrets['benchmark_postgres'] : [];
$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;

$tests = [];

foreach (benchmarkScenarioMatrix([
	'namespace' => 'ActiveRecord',
	'driver' => 'sqlite',
	'description' => 'sqlite::memory:',
	'dbname' => ':memory:',
], includeSimple: true) as $test)
	{
	$tests[] = $test;
	}

foreach (benchmarkScenarioMatrix([
	'namespace' => 'ActiveRecord',
	'driver' => 'sqlite',
	'description' => 'sqlite::file:',
	'dbname' => 'activerecord.sqlite',
], includeSimple: true) as $test)
	{
	$tests[] = $test;
	}

foreach (['Atlas', 'Cake', 'CakeCached', 'Cycle', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV3', 'Yii'] as $namespace)
	{
	foreach (benchmarkScenarioMatrix([
		'namespace' => $namespace,
		'driver' => 'sqlite',
		'description' => 'sqlite::memory:',
		'dbname' => ':memory:',
	], includeSimple: true) as $test)
		{
		$tests[] = $test;
		}

	foreach (benchmarkScenarioMatrix([
		'namespace' => $namespace,
		'driver' => 'sqlite',
		'description' => 'sqlite::file:',
		'dbname' => \strtolower($namespace) . '.sqlite',
	], includeSimple: true) as $test)
		{
		$tests[] = $test;
		}
	}

foreach (['Atlas', 'Cake', 'CakeCached', 'Cycle', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV2', 'DivergenceV3', 'Yii'] as $namespace)
	{
	foreach (benchmarkScenarioMatrix([
		'namespace' => $namespace,
		'driver' => 'mysql',
		'description' => 'MariaDB',
	], includeSimple: true) as $test)
		{
		$tests[] = $withSecrets($test, $benchmarkMariaDb);
		}
	}

foreach (['ActiveRecord', 'Atlas', 'Cake', 'CakeCached', 'Cycle', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV3', 'Yii'] as $namespace)
	{
	foreach (benchmarkScenarioMatrix([
		'namespace' => $namespace,
		'driver' => 'pgsql',
		'description' => 'PostgreSQL',
	], includeSimple: true) as $test)
		{
		$tests[] = $withSecrets($test, $benchmarkPostgres);
		}
	}

return [
	'run' => [
		'type' => 'full-matrix',
		'label' => 'default',
	],
	'iterations' => 500,
	'tests' => $tests,
];
