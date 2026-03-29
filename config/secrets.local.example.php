<?php

return [
	'benchmark_mysql' => [
		'host' => '127.0.0.1',
		'port' => 33060,
		'user' => 'benchmark',
		'password' => 'benchmark',
		'dbname' => 'php_orm_sql_benchmarks',
	],
	'benchmark_mariadb' => [
		'host' => '127.0.0.1',
		'port' => 33061,
		'user' => 'benchmark',
		'password' => 'benchmark',
		'dbname' => 'php_orm_sql_benchmarks',
	],
	'benchmark_postgres' => [
		'host' => '127.0.0.1',
		'port' => 54320,
		'user' => 'benchmark',
		'password' => 'benchmark',
		'dbname' => 'php_orm_sql_benchmarks',
	],
	'divergence' => [
		'mysql' => [
			'host' => '127.0.0.1',
			'database' => 'divergence',
			'username' => 'divergence',
			'password' => 'replace-me',
		],
		'dev-mysql' => [
			'host' => '127.0.0.1',
			'database' => 'divergence',
			'username' => 'divergence',
			'password' => 'replace-me',
		],
		'tests-mysql' => [
			'host' => '127.0.0.1',
			'port' => 33060,
			'database' => 'php_orm_sql_benchmarks',
			'username' => 'benchmark',
			'password' => 'benchmark',
		],
		'tests-mysql-socket' => [
			'socket' => '/var/run/mysqld/mysqld.sock',
			'database' => 'php_orm_sql_benchmarks',
			'username' => 'benchmark',
			'password' => 'benchmark',
		],
	],
];
