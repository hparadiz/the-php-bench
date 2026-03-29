<?php

namespace ThePHPBench;

/**
 * Helper class to load the schema for scaffolding setup, not used for tests
 */
class SchemaLoader
	{
	public function __construct()
		{
		\PHPFUI\ORM::addConnection(new \PHPFUI\ORM\PDOInstance('sqlite::memory:'));

		$lines = \file(__DIR__ . '/../northwind/northwind-schema.sqlite');

		$sql = '';

		foreach ($lines as $line)
			{

			// Ignoring comments from the SQL script
			if (\str_starts_with((string)$line, '--') || '' == $line)
				{
				continue;
				}

			$sql .= $line;

			if (\str_ends_with(\trim((string)$line), ';'))
				{
				\PHPFUI\ORM::execute($sql);
				$lastError = \PHPFUI\ORM::getLastError();

				if ($lastError)
					{
					throw new \Exception($lastError . ' SQL: ' . $sql);
					}
				$sql = '';
				}
			} // end foreach
		}
	}
