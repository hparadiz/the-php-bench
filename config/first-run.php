<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';

$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null)
	? $sharedSecrets['benchmark_mariadb']
	: (\is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : []);

$withMariaDb = static fn(array $config) : array => $benchmarkMariaDb ? \array_replace($config, $benchmarkMariaDb) : $config;
$simple = static fn(array $config) : array => \array_replace(['scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $config);

return [
	'run' => [
		'type' => 'first-run',
		'label' => 'mariadb',
	],
	'iterations' => 5000,
	'tests' => [
		$simple(['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$withMariaDb($simple(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withMariaDb($simple(['namespace' => 'DivergenceV3', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		$withMariaDb(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withMariaDb(['namespace' => 'DivergenceV3', 'driver' => 'mysql', 'description' => 'MariaDB']),
	],
];
