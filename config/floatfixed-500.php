<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';

$benchmarkMySql = \is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : [];
$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null) ? $sharedSecrets['benchmark_mariadb'] : $benchmarkMySql;
$benchmarkPostgres = \is_array($sharedSecrets['benchmark_postgres'] ?? null) ? $sharedSecrets['benchmark_postgres'] : [];
$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;
$floatFixed = static fn(array $config) : array => \array_replace([
	'scenario' => 'float_fixed.500.indexed',
	'iterations' => 500,
], $config);

$tests = [
	$floatFixed(['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'FloatFixed 500 sqlite::memory:', 'dbname' => ':memory:']),
	$floatFixed(['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'FloatFixed 500 sqlite::file:', 'dbname' => 'activerecord.sqlite']),
	$withSecrets($floatFixed(['namespace' => 'ActiveRecord', 'driver' => 'pgsql', 'description' => 'FloatFixed 500 PostgreSQL']), $benchmarkPostgres),
];

foreach (['Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV3'] as $namespace)
	{
	$tests[] = $floatFixed(['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'FloatFixed 500 sqlite::memory:', 'dbname' => ':memory:']);
	$tests[] = $floatFixed(['namespace' => $namespace, 'driver' => 'sqlite', 'description' => 'FloatFixed 500 sqlite::file:', 'dbname' => \strtolower($namespace) . '.sqlite']);
	$tests[] = $withSecrets($floatFixed(['namespace' => $namespace, 'driver' => 'mysql', 'description' => 'FloatFixed 500 MariaDB']), $benchmarkMariaDb);
	}

foreach (['Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean', 'DivergenceV2'] as $namespace)
	{
	if ('DivergenceV2' !== $namespace)
		{
		$tests[] = $withSecrets($floatFixed(['namespace' => $namespace, 'driver' => 'pgsql', 'description' => 'FloatFixed 500 PostgreSQL']), $benchmarkPostgres);
		}
	}

$tests[] = $withSecrets($floatFixed(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'FloatFixed 500 MariaDB']), $benchmarkMariaDb);

return [
	'run' => [
		'type' => 'focused',
		'label' => 'floatfixed-500',
	],
	'iterations' => 500,
	'tests' => $tests,
];
