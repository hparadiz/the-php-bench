<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';

$benchmarkMySql = \is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : [];
$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null) ? $sharedSecrets['benchmark_mariadb'] : $benchmarkMySql;
$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;

return [
	'iterations' => 1, // default is 5000
	'tests' => [
		['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'sqlite::file:', 'dbname' => 'activerecord.sqlite'],
		['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
// $withSecrets(['namespace' => 'ActiveRecord', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb), // not working due to SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0; not working
		$withSecrets(['namespace' => 'ActiveRecord', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		['namespace' => 'Cake', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Cake', 'description' => 'sqlite::file:', 'dbname' => 'cake.sqlite'],
		$withSecrets(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'CakeCached', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'CakeCached', 'description' => 'sqlite::file:', 'dbname' => 'cakecached.sqlite'],
		$withSecrets(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'Doctrine', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Doctrine', 'description' => 'sqlite::file:', 'dbname' => 'doctrine.sqlite'],
		$withSecrets(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'Eloquent', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Eloquent', 'description' => 'sqlite::file:', 'dbname' => 'eloquent.sqlite'],
		$withSecrets(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'PHPFUI', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'PHPFUI', 'description' => 'sqlite::file:', 'dbname' => 'phpfui.sqlite'],
		$withSecrets(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'PHPFUIBatch', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'PHPFUIBatch', 'description' => 'sqlite::file:', 'dbname' => 'phpfuibatch.sqlite'],
		$withSecrets(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'Propel2', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Propel2', 'description' => 'sqlite::file:', 'dbname' => 'propel2.sqlite'],
		$withSecrets(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
		['namespace' => 'RedBean', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'RedBean', 'description' => 'sqlite::file:', 'dbname' => 'redbean.sqlite'],
		$withSecrets(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'MySQL'], $benchmarkMySql),
		$withSecrets(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'MariaDB', 'port' => 3307], $benchmarkMariaDb),
	],
];
