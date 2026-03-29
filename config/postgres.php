<?php

$sharedSecrets = include __DIR__ . '/load-secrets.php';
require_once __DIR__ . '/build-matrix.php';

$benchmarkPostgres = \is_array($sharedSecrets['benchmark_postgres'] ?? null) ? $sharedSecrets['benchmark_postgres'] : [];
$withSecrets = static fn(array $config, array $secrets) : array => $secrets ? \array_replace($config, $secrets) : $config;

$tests = [];

foreach (['ActiveRecord', 'Cake', 'CakeCached', 'Doctrine', 'Eloquent', 'PHPFUI', 'PHPFUIBatch', 'Propel2', 'RedBean'] as $namespace)
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
		'label' => 'postgres',
	],
	'iterations' => 500,
	'tests' => $tests,
];
