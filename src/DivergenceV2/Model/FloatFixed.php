<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV2\Model;

use Divergence\Models\ActiveRecord;
use Divergence\Models\Getters;
use Divergence\Models\Mapping\Column;

class FloatFixed extends ActiveRecord
	{
	use Getters;

	public static $tableName = 'float_fixed_records';
	public static $primaryKey = 'float_fixed_id';
	public static $singularNoun = 'float fixed record';
	public static $pluralNoun = 'float fixed records';

	public static $indexes = [
		'n01' => ['fields' => ['n01']],
		'n03' => ['fields' => ['n03']],
	];

	#[Column(type: 'integer', primary: true, autoincrement: true, unsigned: true)]
	protected $float_fixed_id;

	#[Column(type: 'float')] protected $f01;
	#[Column(type: 'float')] protected $f02;
	#[Column(type: 'float')] protected $d01;
	#[Column(type: 'float')] protected $d02;
	#[Column(type: 'decimal', precision: 10, scale: 2)] protected $n01;
	#[Column(type: 'decimal', precision: 12, scale: 4)] protected $n02;
	#[Column(type: 'decimal', precision: 18, scale: 6)] protected $n03;
	#[Column(type: 'decimal', precision: 20, scale: 8)] protected $n04;
	}
