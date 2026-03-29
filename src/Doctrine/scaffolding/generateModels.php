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
	echo "{$table}\n";
	\generate($table);
	}

function generate(string $table) : void
	{
	$template = \file_get_contents(__DIR__ . '/model.template');
	$parameters['~TABLE~'] = $table;
	$class = \PHPFUI\ORM::getBaseClassName($table);
	$parameters['~CLASS~'] = $class;
	$autoIncrement = false;
	$commentedFields = [];

	$docBlock = $fields = '';

	foreach (\PHPFUI\ORM::describeTable($table) as $field)
		{
		$fields .= \getField($field);

		$comment = \getComment($field, $commentedFields);

		if ($comment)
			{
			$docBlock .= " * @property {$comment}\n";
			}
		}

	$parameters['~FIELDS~'] = $fields;
	$parameters['~DOC_BLOCK~'] = $docBlock;

	$fileName = __DIR__ . '/../Entity/' . $class . '.php';

	foreach ($parameters as $key => $value)
		{
		$template = \str_replace($key, $value, $template);
		}

	\file_put_contents($fileName, $template);
	}

function getField(\PHPFUI\ORM\Schema\Field $field) : string
	{
	$text = '';

	$orm = [];

	if ($field->primaryKey)
		{
		$orm[] = '\Doctrine\ORM\Mapping\Id';
		}

	if ($field->autoIncrement)
		{
		$orm[] = '\Doctrine\ORM\Mapping\GeneratedValue(strategy: "AUTO")';
		}

	$descriptions = [];

	$type = $field->type;
	$parts = [];

	if ($pos = \strpos($type, '('))
		{
		$parts = \explode(',', \substr($type, $pos + 1, \strlen($type) - $pos - 2));
		}

	if (\str_starts_with($type, 'decimal'))
		{
		$descriptions['type'] = '"decimal"';
		$descriptions['precision'] = $parts[0];
		$descriptions['scale'] = $parts[1];
		}
	elseif (\str_starts_with($type, 'varchar'))
		{
		$descriptions['type'] = '"string"';
		$descriptions['length'] = $parts[0];
		}
	else
		{
		$descriptions['type'] = '"' . \str_replace('long', '', $type) . '"';
		}

	if ($field->defaultValue && 'NULL' !== $field->defaultValue)
		{
		$descriptions['options'] = '["default" => "' . \str_replace(['"', "'"], '', $field->defaultValue) . '"]';
		}

	$nullable = '';

	if ($field->nullable)
		{
		$descriptions['nullable'] = 'true';
		$nullable = '?';
		}

	$text = '';

	foreach ($orm as $ormType)
		{
		$text .= "\t#[{$ormType}]\n";
		}
	$text .= "\t#[\Doctrine\ORM\Mapping\Column(";
	$comma = '';

	foreach ($descriptions as $descriptionType => $value)
		{
		$text .= $comma . $descriptionType . ': ' . $value;
		$comma = ', ';
		}
	$text .= ")]\n";
	$phptype = \getPHPType($field->type);
	$phpDefault = '';

	if ($field->defaultValue && 'CURRENT_TIMESTAMP' !== $field->defaultValue)
		{
		$defaultValue = \str_replace(['"', "'"], '', $field->defaultValue);

		if (\is_numeric($defaultValue))
			{
			$phpDefault = ' = ' . $defaultValue;
			}
		else
			{
			$phpDefault = ' = ' . $field->defaultValue;
			}
		}
	$dollar = '$';
	$text .= "\tpublic {$nullable}{$phptype} {$dollar}{$field->name}{$phpDefault};\n\n";

	return $text;
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
