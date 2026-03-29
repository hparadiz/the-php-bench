<?php

include __DIR__ . '/../../../vendor/autoload.php';

//$schema = new \ThePHPBench\SchemaLoader();	this uses SQLite

$settings = ['namespace' => 'PHPFUI', 'driver' => 'pgsql', 'description' => 'Postgre', 'port' => 5433, 'user' => 'postgres', 'password' => 'password'];

$config = new \ThePHPBench\Configuration($settings, 0);
$pdo = new \PHPFUI\ORM\PDOInstance($config->getPDOConnectionString(), $config->getUser(), $config->getPassword());

\PHPFUI\ORM::addConnection($pdo);
\PHPFUI\ORM::$namespaceRoot = __DIR__ . '/../../..';
\PHPFUI\ORM::$recordNamespace = 'ThePHPBench\PHPFUI\Record';
\PHPFUI\ORM::$tableNamespace = 'ThePHPBench\PHPFUI\Table';
\PHPFUI\ORM::$migrationNamespace = 'ThePHPBench\PHPFUI\Migration';
\PHPFUI\ORM::$idSuffix = '_id';

echo "Generate Record Models\n\n";

$toolClass = '\\PHPFUI\\ORM\\Tool\\Generate\\' . 'CR' . 'UD';
$generator = new $toolClass();

$tables = \PHPFUI\ORM::getTables();

if (! \count($tables))
	{
	echo "No tables found. Check your database configuration settings.\n";

	exit;
	}

foreach ($tables as $table)
	{
	if ($generator->generate($table))
		{
		echo "{$table}\n";
		}
	}
