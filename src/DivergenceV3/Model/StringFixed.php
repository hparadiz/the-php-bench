<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV3\Model;

use Divergence\Models\ActiveRecord;
use Divergence\Models\Getters;
use Divergence\Models\Mapping\Column;

class StringFixed extends ActiveRecord
	{
	use Getters;

	public static $tableName = 'string_fixed_records';
	public static $primaryKey = 'string_fixed_id';
	public static $singularNoun = 'string fixed record';
	public static $pluralNoun = 'string fixed records';

	public static $indexes = [
		'c08_01' => ['fields' => ['c08_01']],
		'c16_01' => ['fields' => ['c16_01']],
		'c32_01' => ['fields' => ['c32_01']],
	];

	#[Column(type: 'integer', primary: true, autoincrement: true, unsigned: true)]
	protected ?int $string_fixed_id = null;

	#[Column(type: 'string', length: 8)] protected string $c08_01;
	#[Column(type: 'string', length: 8)] protected string $c08_02;
	#[Column(type: 'string', length: 16)] protected string $c16_01;
	#[Column(type: 'string', length: 16)] protected string $c16_02;
	#[Column(type: 'string', length: 32)] protected string $c32_01;
	#[Column(type: 'string', length: 32)] protected string $c32_02;
	#[Column(type: 'string', length: 64)] protected string $c64_01;
	#[Column(type: 'string', length: 64)] protected string $c64_02;
	}
