<?php

return [
	'run' => [
		'type' => 'smoke',
		'label' => 'sqlite',
	],
	'iterations' => 5000,
	'tests' => [
		['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'Cake', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'CakeCached', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'Doctrine', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'Eloquent', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'PHPFUI', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'PHPFUIBatch', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'Propel2', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'RedBean', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'DivergenceV3', 'driver' => 'sqlite', 'description' => 'Simple sqlite::memory:', 'dbname' => ':memory:', 'scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0],
		['namespace' => 'ActiveRecord', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Cake', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'CakeCached', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Doctrine', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Eloquent', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'PHPFUI', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'PHPFUIBatch', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'Propel2', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'RedBean', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
		['namespace' => 'DivergenceV3', 'driver' => 'sqlite', 'description' => 'sqlite::memory:', 'dbname' => ':memory:'],
	],
];
