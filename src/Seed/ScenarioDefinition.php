<?php

declare(strict_types=1);

namespace ThePHPBench\Seed;

final class ScenarioDefinition
	{
	/**
	 * @param list<FieldDefinition> $fields
	 * @param list<string> $indexedColumns
	 */
	public function __construct(
		public readonly string $id,
		public readonly string $modelFamily,
		public readonly string $tableName,
		public readonly string $primaryKey,
		public readonly int $rowCount,
		public readonly bool $indexed,
		public readonly array $fields,
		public readonly array $indexedColumns,
	) {}

	public function getIndexMode() : string
		{
		return $this->indexed ? 'indexed' : 'unindexed';
		}

	public function getTableName() : string
		{
		return $this->tableName;
		}
	}
