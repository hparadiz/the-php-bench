<?php

include __DIR__ . '/../../../vendor/autoload.php';

$schema = new \ThePHPBench\SchemaLoader();

echo "Generate Record Models\n\n";

$tables = \PHPFUI\ORM::getTables();

if (! \count($tables))
	{
	echo "No tables found. Check your database configuration settings.\n";

	exit;
	}

foreach ($tables as $table)
	{
	\generate($table);
	echo "{$table}\n";
	}

function generate(string $table) : void
	{
	$template = \file_get_contents(__DIR__ . '/model.template');
	$parameters['~TABLE~'] = $table;
	$class = \PHPFUI\ORM::getBaseClassName($table);
	$parameters['~CLASS~'] = $class;
	$fields = \PHPFUI\ORM::describeTable($table);
	$autoIncrement = false;
	$commentedFields = [];

	$docBlock = '';

	foreach ($fields as $field)
		{
		if ($field->autoIncrement)
			{
			$autoIncrement = true;
			}

		if ($field->primaryKey)
			{
			$parameters['~PRIMARY_KEY~'] = $field->name;
			}
		$comment = \getComment($field, $commentedFields);

		if ($comment)
			{
			$docBlock .= " * @property {$comment}\n";
			}
		}

	$parameters['~AUTO_INCREMENT~'] = $autoIncrement ? 'true' : 'false';
	$parameters['~DOC_BLOCK~'] = $docBlock;

	$fileName = __DIR__ . '/../Model/' . $class . '.php';

	foreach ($parameters as $key => $value)
		{
		$template = \str_replace($key, $value, $template);
		}

	\file_put_contents($fileName, $template);
	}

	/**
	 * @param array<string,true> &$commentedFields
	 */
function getComment(\PHPFUI\ORM\Schema\Field $field, array &$commentedFields) : ?string
	{
	$fieldName = $field->name;

	if (isset($commentedFields[$fieldName]))
		{
		return null;
		}
	$commentedFields[$fieldName] = true;
	$mySQLType = \str_replace("'", '', $field->type);
	$phpType = \getPHPType($mySQLType);
	$null = $field->nullable ? '?' : '';

	$block = $null . $phpType . ' $' . $fieldName . ' MySQL type ' . $mySQLType;
	$commentedFields[$fieldName] = true;

	return $block;
	}

function getPHPType(string $type) : string
	{
	$start = \strpos($type, '(');

	if (false !== $start)
		{
		$type = \substr($type, 0, $start);
		}

	static $types = [
		'integer' => 'int',
		'int' => 'int',
		'int unsigned' => 'int',
		'smallint' => 'int',
		'tinyint' => 'int',
		'mediumint' => 'int',
		'bigint' => 'int',
		'decimal' => 'float',
		'numeric' => 'float',
		'float' => 'float',
		'double' => 'float',
		'bit' => 'bool',
		'year' => 'int',
	];

	return $types[\strtolower($type)] ?? 'string';
	}
