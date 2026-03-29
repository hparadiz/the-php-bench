<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';

$benchmarkMySql = \is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : [];
$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null) ? $sharedSecrets['benchmark_mariadb'] : $benchmarkMySql;
$benchmarkPostgres = \is_array($sharedSecrets['benchmark_postgres'] ?? null) ? $sharedSecrets['benchmark_postgres'] : [];
$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;
$simple = static fn(array $config) : array => \array_replace([
	'scenario' => 'simple.10.indexed',
	'iterations' => 10,
	'priority' => 0,
], $config);

return [
	'run' => [
		'type' => 'simple',
		'label' => 'full-matrix',
	],
	'iterations' => 10,
	'tests' => [
		$simple(['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-activerecord.sqlite']),
		$withSecrets($simple(['namespace' => 'ActiveRecord', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'ActiveRecord', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Atlas', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Atlas', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-atlas.sqlite']),
		$withSecrets($simple(['namespace' => 'Atlas', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Atlas', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Cake', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Cake', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-cake.sqlite']),
		$withSecrets($simple(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'Cake', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'CakeCached', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'CakeCached', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-cakecached.sqlite']),
		$withSecrets($simple(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'CakeCached', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Cycle', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Cycle', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-cycle.sqlite']),
		$withSecrets($simple(['namespace' => 'Cycle', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Cycle', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Doctrine', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Doctrine', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-doctrine.sqlite']),
		$withSecrets($simple(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'Doctrine', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Eloquent', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Eloquent', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-eloquent.sqlite']),
		$withSecrets($simple(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'Eloquent', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'PHPFUI', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'PHPFUI', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-phpfui.sqlite']),
		$withSecrets($simple(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'PHPFUI', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'PHPFUIBatch', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'PHPFUIBatch', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-phpfuibatch.sqlite']),
		$withSecrets($simple(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'PHPFUIBatch', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Propel2', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Propel2', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-propel2.sqlite']),
		$withSecrets($simple(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'Propel2', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'RedBean', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'RedBean', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-redbean.sqlite']),
		$withSecrets($simple(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'RedBean', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$simple(['namespace' => 'Yii', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'Yii', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-yii.sqlite']),
		$withSecrets($simple(['namespace' => 'Yii', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'Yii', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),

		$withSecrets($simple(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),

		$simple(['namespace' => 'DivergenceV3', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:']),
		$simple(['namespace' => 'DivergenceV3', 'driver' => 'sqlite', 'description' => 'Simple sqlite::file:', 'dbname' => 'simple-divergencev3.sqlite']),
		$withSecrets($simple(['namespace' => 'DivergenceV3', 'driver' => 'mysql', 'description' => 'Simple MySQL']), $benchmarkMySql),
		$withSecrets($simple(['namespace' => 'DivergenceV3', 'driver' => 'mysql', 'description' => 'Simple MariaDB', 'port' => 3307]), $benchmarkMariaDb),
		$withSecrets($simple(['namespace' => 'DivergenceV3', 'driver' => 'pgsql', 'description' => 'Simple PostgreSQL']), $benchmarkPostgres),
	],
];
