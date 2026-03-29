<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';

$benchmarkMariaDb = \is_array($sharedSecrets['benchmark_mariadb'] ?? null)
	? $sharedSecrets['benchmark_mariadb']
	: (\is_array($sharedSecrets['benchmark_mysql'] ?? null) ? $sharedSecrets['benchmark_mysql'] : []);

$withSecrets = static fn(array $config) : array => $benchmarkMariaDb ? \array_replace($config, $benchmarkMariaDb) : $config;
$simple = static fn(array $config) : array => \array_replace(['scenario' => 'simple.10.indexed', 'iterations' => 10, 'priority' => 0], $config);

return [
	'run' => [
		'type' => 'smoke',
		'label' => 'mariadb',
	],
	'iterations' => 5000,
	'tests' => [
		$withSecrets($simple(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets($simple(['namespace' => 'DivergenceV3', 'driver' => 'mysql', 'description' => 'Simple MariaDB'])),
		$withSecrets(['namespace' => 'Cake', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'CakeCached', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'Doctrine', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'Eloquent', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'PHPFUI', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'PHPFUIBatch', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'Propel2', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'RedBean', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'DivergenceV2', 'driver' => 'mysql', 'description' => 'MariaDB']),
		$withSecrets(['namespace' => 'DivergenceV3', 'driver' => 'mysql', 'description' => 'MariaDB']),
	],
];
