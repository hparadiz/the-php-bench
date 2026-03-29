<?php

declare(strict_types=1);

namespace ThePHPBench;

use ThePHPBench\Seed\FieldDefinition;
use ThePHPBench\Seed\ScenarioRegistry;

final class CanonicalModelGenerator
	{
	public static function generate(?string $root = null) : void
		{
		$root ??= \dirname(__DIR__);
		$families = [
			'simple' => ScenarioRegistry::get('simple.10.indexed'),
			'canary' => ScenarioRegistry::get('canary.500.indexed'),
			'int_fixed' => ScenarioRegistry::get('int_fixed.500.indexed'),
			'float_fixed' => ScenarioRegistry::get('float_fixed.500.indexed'),
			'string_fixed' => ScenarioRegistry::get('string_fixed.500.indexed'),
			'string_variable' => ScenarioRegistry::get('string_variable.500.indexed'),
		];

		self::writeCake($root, 'Cake', $families);
		self::writeCake($root, 'CakeCached', $families);
		self::writeActiveRecord($root, $families);
		self::writeEloquent($root, $families);
		self::writeDoctrine($root, $families);
		self::writePHPFUI($root, $families);
		}

	private static function className(string $family) : string
		{
		return match ($family) {
			'simple' => 'Simple',
			'canary' => 'Canary',
			'int_fixed' => 'IntFixed',
			'float_fixed' => 'FloatFixed',
			'string_fixed' => 'StringFixed',
			'string_variable' => 'StringVariable',
			default => throw new \InvalidArgumentException("Unknown family {$family}"),
		};
		}

	private static function writeFile(string $path, string $contents) : void
		{
		$dir = \dirname($path);

		if (! \is_dir($dir))
			{
			\mkdir($dir, 0777, true);
			}

		\file_put_contents($path, $contents);
		}

	private static function phpType(FieldDefinition $field, string $context = 'default') : string
		{
		return match ($field->type) {
			'id', 'smallint', 'integer', 'bigint' => 'int',
			'float', 'double' => 'float',
			'decimal' => 'doctrine' === $context ? 'string' : 'float',
			'boolean' => 'bool',
			'date' => 'doctrine' === $context ? '\\DateTimeImmutable' : 'string',
			'datetime' => 'doctrine' === $context ? '\\DateTimeImmutable' : 'string',
			'char', 'varchar', 'text' => 'string',
			default => 'string',
		};
		}

	private static function sqlType(FieldDefinition $field) : string
		{
		return match ($field->type) {
			'id', 'integer' => 'integer',
			'smallint' => 'smallint',
			'bigint' => 'bigint',
			'float' => 'float',
			'double' => 'double',
			'decimal' => "decimal({$field->precision},{$field->scale})",
			'char' => "char({$field->length})",
			'varchar' => "varchar({$field->length})",
			'text' => 'longtext',
			'boolean' => 'boolean',
			'date' => 'date',
			'datetime' => 'datetime',
			default => 'text',
		};
		}

	private static function doctrineColumn(FieldDefinition $field) : string
		{
		$options = [];

		if ('id' === $field->type)
			{
			return "#[ORM\\Id]\n\t#[ORM\\Column(type: 'integer')]";
			}

		$type = match ($field->type) {
			'smallint' => 'smallint',
			'integer' => 'integer',
			'bigint' => 'bigint',
			'float', 'double' => 'float',
			'decimal' => 'decimal',
			'char', 'varchar' => 'string',
			'text' => 'text',
			'boolean' => 'boolean',
			'date' => 'date_immutable',
			'datetime' => 'datetime_immutable',
			default => 'string',
		};

		$options[] = "type: '{$type}'";

		if (\in_array($field->type, ['char', 'varchar'], true))
			{
			$options[] = 'length: ' . $field->length;
			}

		if ('decimal' === $field->type)
			{
			$options[] = 'precision: ' . $field->precision;
			$options[] = 'scale: ' . $field->scale;
			}

		if ($field->nullable)
			{
			$options[] = 'nullable: true';
			}

		return '#[ORM\\Column(' . \implode(', ', $options) . ')]';
		}

	private static function writeActiveRecord(string $root, array $families) : void
		{
		foreach ($families as $family => $scenario)
			{
			$class = self::className($family);
			self::writeFile(
				"{$root}/src/ActiveRecord/Model/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\ActiveRecord\\Model;\n\nclass {$class} extends \\ActiveRecord\\Model\n\t{\n\tpublic static string \$table_name = '{$scenario->tableName}';\n\n\tpublic static string \$primary_key = '{$scenario->primaryKey}';\n\n\tpublic static string \$sequence = '{$scenario->tableName}_{$scenario->primaryKey}_seq';\n\t}\n"
			);
			}
		}

	private static function writeCake(string $root, string $namespace, array $families) : void
		{
		self::writeFile(
			"{$root}/src/{$namespace}/Record/BaseEntity.php",
			"<?php\n\nnamespace ThePHPBench\\{$namespace}\\Record;\n\nclass BaseEntity extends \\Cake\\ORM\\Entity\n\t{\n\tprotected array \$_accessible = ['*' => true];\n\t}\n"
		);

		foreach ($families as $family => $scenario)
			{
			$class = self::className($family);
			self::writeFile(
				"{$root}/src/{$namespace}/Record/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\{$namespace}\\Record;\n\nclass {$class} extends BaseEntity\n\t{\n\t}\n"
			);
			self::writeFile(
				"{$root}/src/{$namespace}/Table/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\{$namespace}\\Table;\n\nclass {$class} extends \\Cake\\ORM\\Table\n\t{\n\tpublic function initialize(array \$config) : void\n\t\t{\n\t\tparent::initialize(\$config);\n\t\t\$this->setTable('{$scenario->tableName}');\n\t\t\$this->setPrimaryKey('{$scenario->primaryKey}');\n\t\t\$this->setEntityClass('ThePHPBench\\\\{$namespace}\\\\Record\\\\{$class}');\n\t\t}\n\t}\n"
			);
			}
		}

	private static function writeEloquent(string $root, array $families) : void
		{
		foreach ($families as $family => $scenario)
			{
			$class = self::className($family);
			$casts = [];

			foreach ($scenario->fields as $field)
				{
				if ($field->name === $scenario->primaryKey)
					{
					continue;
					}

				$casts[$field->name] = match ($field->type) {
					'boolean' => 'boolean',
					'date' => 'date',
					'datetime' => 'datetime',
					'decimal' => 'decimal:' . $field->scale,
					default => null,
				};
				}

			$casts = \array_filter($casts);
			$castCode = '[]';

			if ($casts)
				{
				$lines = [];

				foreach ($casts as $field => $cast)
					{
					$lines[] = "\t\t'{$field}' => '{$cast}',";
					}

				$castCode = "[\n" . \implode("\n", $lines) . "\n\t]";
				}

			self::writeFile(
				"{$root}/src/Eloquent/Model/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\Eloquent\\Model;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass {$class} extends Model\n\t{\n\tpublic \$incrementing = true;\n\n\tpublic \$timestamps = false;\n\n\tprotected \$guarded = [];\n\n\tprotected \$primaryKey = '{$scenario->primaryKey}';\n\n\tprotected \$table = '{$scenario->tableName}';\n\n\tprotected \$casts = {$castCode};\n\t}\n"
			);
			}
		}

	private static function writeDoctrine(string $root, array $families) : void
		{
		foreach ($families as $family => $scenario)
			{
			$class = self::className($family);
			$properties = '';

			foreach ($scenario->fields as $field)
				{
				$type = self::phpType($field, 'doctrine');
				$nullable = $field->nullable ? '?' : '';
				$default = $field->nullable ? ' = null' : '';

				$properties .= "\t" . self::doctrineColumn($field) . "\n";
				$properties .= "\tpublic {$nullable}{$type} \${$field->name}{$default};\n\n";
				}

			self::writeFile(
				"{$root}/src/Doctrine/Entity/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\Doctrine\\Entity;\n\nuse Doctrine\\ORM\\Mapping as ORM;\n\n#[ORM\\Entity]\n#[ORM\\Table(name: '{$scenario->tableName}')]\nclass {$class}\n\t{\n{$properties}\t}\n"
			);
			}
		}

	private static function writePHPFUI(string $root, array $families) : void
		{
		foreach ($families as $family => $scenario)
			{
			$class = self::className($family);
			$fields = [];
			$doc = [];

			foreach ($scenario->fields as $field)
				{
				$type = self::phpType($field);
				$nullable = $field->nullable ? '?' : '';
				$doc[] = " * @property {$nullable}{$type} \${$field->name} MySQL type " . self::sqlType($field);
				$fields[] = "\t\t\t\t'{$field->name}' => new \\PHPFUI\\ORM\\FieldDefinition('" . self::sqlType($field) . "', '{$type}', " . (int)($field->length ?? 0) . ', ' . ($field->nullable ? 'true' : 'false') . '),';
				}

			$definition = "<?php\n\nnamespace ThePHPBench\\PHPFUI\\Record\\Definition;\n\n/**\n * Autogenerated canonical benchmark definition.\n *\n" . \implode("\n", $doc) . "\n */\nabstract class {$class} extends \\PHPFUI\\ORM\\Record\n\t{\n\tprotected static bool \$autoIncrement = false;\n\n\tprotected static array \$fields = [];\n\n\tprotected static array \$primaryKeys = ['{$scenario->primaryKey}'];\n\n\tprotected static string \$table = '{$scenario->tableName}';\n\n\tpublic function initFieldDefinitions() : static\n\t\t{\n\t\tif (! \\count(static::\$fields))\n\t\t\t{\n\t\t\tstatic::\$fields = [\n" . \implode("\n", $fields) . "\n\t\t\t];\n\t\t\t}\n\n\t\treturn \$this;\n\t\t}\n\t}\n";

			self::writeFile("{$root}/src/PHPFUI/Record/Definition/{$class}.php", $definition);
			self::writeFile(
				"{$root}/src/PHPFUI/Record/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\PHPFUI\\Record;\n\nclass {$class} extends \\ThePHPBench\\PHPFUI\\Record\\Definition\\{$class}\n\t{\n\t}\n"
			);
			self::writeFile(
				"{$root}/src/PHPFUI/Table/{$class}.php",
				"<?php\n\nnamespace ThePHPBench\\PHPFUI\\Table;\n\nclass {$class} extends \\PHPFUI\\ORM\\Table\n\t{\n\tprotected static string \$className = \\ThePHPBench\\PHPFUI\\Record\\{$class}::class;\n\t}\n"
			);
			}
		}
	}
