<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV3\Model;

use Divergence\Models\ActiveRecord;
use Divergence\Models\Getters;
use Divergence\Models\Mapping\Column;

class Canary extends ActiveRecord
	{
	use Getters;

	public static $tableName = 'canaries';
	public static $primaryKey = 'canary_id';
	public static $singularNoun = 'canary';
	public static $pluralNoun = 'canaries';

	public static $indexes = [
		'string_short' => ['fields' => ['string_short']],
		'int_value' => ['fields' => ['int_value']],
		'date_value' => ['fields' => ['date_value']],
	];

	#[Column(type: 'integer', primary: true, autoincrement: true, unsigned: true)]
	protected ?int $canary_id = null;

	#[Column(type: 'boolean')]
	protected bool $bool_flag;

	#[Column(type: 'int')]
	protected int $small_int;

	#[Column(type: 'int')]
	protected int $int_value;

	#[Column(type: 'int')]
	protected int $big_int;

	#[Column(type: 'float')]
	protected float $float_value;

	#[Column(type: 'float')]
	protected float $double_value;

	#[Column(type: 'decimal', precision: 12, scale: 4)]
	protected float $decimal_value;

	#[Column(type: 'string', length: 8)]
	protected string $fixed_char_8;

	#[Column(type: 'string', length: 16)]
	protected string $fixed_char_16;

	#[Column(type: 'string', length: 32)]
	protected string $string_short;

	#[Column(type: 'string', length: 128)]
	protected string $string_medium;

	#[Column(type: 'string', length: 512)]
	protected string $string_long;

	#[Column(type: 'clob')]
	protected string $text_value;

	#[Column(type: 'date')]
	protected string $date_value;

	#[Column(type: 'timestamp')]
	protected ?string $datetime_value = null;

	#[Column(type: 'string', length: 64)]
	protected ?string $nullable_string = null;

	#[Column(type: 'int')]
	protected ?int $nullable_int = null;
	}
