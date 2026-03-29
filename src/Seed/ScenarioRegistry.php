<?php

declare(strict_types=1);

namespace ThePHPBench\Seed;

final class ScenarioRegistry
	{
	/** @return array<string, ScenarioDefinition> */
	public static function all() : array
		{
		$definitions = [];

		foreach (self::families() as $modelFamily => $definition)
			{
			foreach (self::rowCountsForFamily($modelFamily) as $rowCount)
				{
				foreach ([true, false] as $indexed)
					{
					$id = "{$modelFamily}.{$rowCount}." . ($indexed ? 'indexed' : 'unindexed');
					$definitions[$id] = new ScenarioDefinition(
						$id,
						$modelFamily,
						$definition['tableName'],
						$definition['primaryKey'],
						$rowCount,
						$indexed,
						$definition['fields'],
						$indexed ? $definition['indexedColumns'] : [],
					);
					}
				}
			}

		\ksort($definitions);

		return $definitions;
		}

	public static function get(string $id) : ScenarioDefinition
		{
		$definitions = self::all();

		if (! isset($definitions[$id]))
			{
			throw new \InvalidArgumentException("Unknown scenario id: {$id}");
			}

		return $definitions[$id];
		}

	/**
	 * @return array<string, array{tableName: string, primaryKey: string, fields: list<FieldDefinition>, indexedColumns: list<string>}>
	 */
	private static function families() : array
		{
		return [
			'simple' => [
				'tableName' => 'simple_records',
				'primaryKey' => 'simple_id',
				'fields' => [
					new FieldDefinition('simple_id', 'id'),
					new FieldDefinition('title', 'varchar', length: 128),
				],
				'indexedColumns' => ['title'],
			],
			'canary' => [
				'tableName' => 'canaries',
				'primaryKey' => 'canary_id',
				'fields' => [
					new FieldDefinition('canary_id', 'id'),
					new FieldDefinition('bool_flag', 'boolean'),
					new FieldDefinition('small_int', 'smallint'),
					new FieldDefinition('int_value', 'integer'),
					new FieldDefinition('big_int', 'bigint'),
					new FieldDefinition('float_value', 'float'),
					new FieldDefinition('double_value', 'double'),
					new FieldDefinition('decimal_value', 'decimal', precision: 12, scale: 4),
					new FieldDefinition('fixed_char_8', 'char', length: 8),
					new FieldDefinition('fixed_char_16', 'char', length: 16),
					new FieldDefinition('string_short', 'varchar', length: 32),
					new FieldDefinition('string_medium', 'varchar', length: 128),
					new FieldDefinition('string_long', 'varchar', length: 512),
					new FieldDefinition('text_value', 'text'),
					new FieldDefinition('date_value', 'date'),
					new FieldDefinition('datetime_value', 'datetime'),
					new FieldDefinition('nullable_string', 'varchar', true, 64),
					new FieldDefinition('nullable_int', 'integer', true),
				],
				'indexedColumns' => ['string_short', 'int_value', 'date_value'],
			],
			'int_fixed' => [
				'tableName' => 'int_fixed_records',
				'primaryKey' => 'int_fixed_id',
				'fields' => [
					new FieldDefinition('int_fixed_id', 'id'),
					new FieldDefinition('i01', 'integer'),
					new FieldDefinition('i02', 'integer'),
					new FieldDefinition('i03', 'integer'),
					new FieldDefinition('i04', 'integer'),
					new FieldDefinition('i05', 'integer'),
					new FieldDefinition('i06', 'integer'),
					new FieldDefinition('i07', 'integer'),
					new FieldDefinition('i08', 'integer'),
					new FieldDefinition('i09', 'integer'),
					new FieldDefinition('i10', 'integer'),
					new FieldDefinition('i11', 'integer'),
					new FieldDefinition('i12', 'integer'),
				],
				'indexedColumns' => ['i01', 'i06', 'i12'],
			],
			'float_fixed' => [
				'tableName' => 'float_fixed_records',
				'primaryKey' => 'float_fixed_id',
				'fields' => [
					new FieldDefinition('float_fixed_id', 'id'),
					new FieldDefinition('f01', 'float'),
					new FieldDefinition('f02', 'float'),
					new FieldDefinition('d01', 'double'),
					new FieldDefinition('d02', 'double'),
					new FieldDefinition('n01', 'decimal', precision: 10, scale: 2),
					new FieldDefinition('n02', 'decimal', precision: 12, scale: 4),
					new FieldDefinition('n03', 'decimal', precision: 18, scale: 6),
					new FieldDefinition('n04', 'decimal', precision: 20, scale: 8),
				],
				'indexedColumns' => ['n01', 'n03'],
			],
			'string_fixed' => [
				'tableName' => 'string_fixed_records',
				'primaryKey' => 'string_fixed_id',
				'fields' => [
					new FieldDefinition('string_fixed_id', 'id'),
					new FieldDefinition('c08_01', 'char', length: 8),
					new FieldDefinition('c08_02', 'char', length: 8),
					new FieldDefinition('c16_01', 'char', length: 16),
					new FieldDefinition('c16_02', 'char', length: 16),
					new FieldDefinition('c32_01', 'char', length: 32),
					new FieldDefinition('c32_02', 'char', length: 32),
					new FieldDefinition('c64_01', 'char', length: 64),
					new FieldDefinition('c64_02', 'char', length: 64),
				],
				'indexedColumns' => ['c08_01', 'c16_01', 'c32_01'],
			],
			'string_variable' => [
				'tableName' => 'string_variable_records',
				'primaryKey' => 'string_variable_id',
				'fields' => [
					new FieldDefinition('string_variable_id', 'id'),
					new FieldDefinition('name', 'varchar', length: 64),
					new FieldDefinition('title', 'varchar', length: 128),
					new FieldDefinition('email', 'varchar', length: 160),
					new FieldDefinition('company', 'varchar', length: 160),
					new FieldDefinition('city', 'varchar', length: 64),
					new FieldDefinition('region', 'varchar', length: 64),
					new FieldDefinition('postal_code', 'varchar', length: 32),
					new FieldDefinition('country', 'varchar', length: 64),
					new FieldDefinition('phone', 'varchar', length: 32),
					new FieldDefinition('notes', 'text'),
				],
				'indexedColumns' => ['email', 'company', 'postal_code'],
			],
		];
		}

	/** @return list<int> */
	private static function rowCountsForFamily(string $modelFamily) : array
		{
		return 'simple' === $modelFamily ? [10] : [10, 50, 500];
		}
	}
