<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV2\Model;

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
	protected $canary_id;

	#[Column(type: 'boolean')] protected $bool_flag;
	#[Column(type: 'int')] protected $small_int;
	#[Column(type: 'int')] protected $int_value;
	#[Column(type: 'int')] protected $big_int;
	#[Column(type: 'float')] protected $float_value;
	#[Column(type: 'float')] protected $double_value;
	#[Column(type: 'decimal', precision: 12, scale: 4)] protected $decimal_value;
	#[Column(type: 'string', length: 8)] protected $fixed_char_8;
	#[Column(type: 'string', length: 16)] protected $fixed_char_16;
	#[Column(type: 'string', length: 32)] protected $string_short;
	#[Column(type: 'string', length: 128)] protected $string_medium;
	#[Column(type: 'string', length: 512)] protected $string_long;
	#[Column(type: 'clob')] protected $text_value;
	#[Column(type: 'date')] protected $date_value;
	#[Column(type: 'timestamp', notnull: false)] protected $datetime_value;
	#[Column(type: 'string', length: 64, notnull: false)] protected $nullable_string;
	#[Column(type: 'int', notnull: false)] protected $nullable_int;
	}
