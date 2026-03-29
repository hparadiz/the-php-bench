<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';

$benchmarkMySql = \is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : [];
$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null) ? $sharedSecrets['benchmark_mariadb'] : $benchmarkMySql;
$benchmarkPostgres = \is_array($sharedSecrets['benchmark_postgres'] ?? null) ? $sharedSecrets['benchmark_postgres'] : [];
$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;

$tests = [];

foreach (['ActiveRecord', 'Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV3'] as $namespace)
	{
	if ('ActiveRecord' !== $namespace)
		{
		$tests[] = ['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'Canary 50 sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'canary.50.indexed', 'iterations' => 50];
		}
	}

$tests[] = ['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'Canary 50 sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'canary.50.indexed', 'iterations' => 50];

foreach (['Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV2', 'DivergenceV3'] as $namespace)
	{
	$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'Canary 10 MariaDB', 'scenario' => 'canary.10.indexed', 'iterations' => 10], $benchmarkMariaDb);
	}

foreach (['Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean'] as $namespace)
	{
	$tests[] = $withSecrets(['namespace' => $namespace, 'driver' => 'pgsql', 'description' => 'Canary 10 PostgreSQL', 'scenario' => 'canary.10.indexed', 'iterations' => 10], $benchmarkPostgres);
	}

return [
	'run' => [
		'type' => 'focused',
		'label' => 'canary-check',
	],
	'iterations' => 50,
	'tests' => $tests,
];
