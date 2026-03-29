<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV2\Model;

use Divergence\Models\ActiveRecord;
use Divergence\Models\Getters;
use Divergence\Models\Mapping\Column;

class IntFixed extends ActiveRecord
	{
	use Getters;

	public static $tableName = 'int_fixed_records';
	public static $primaryKey = 'int_fixed_id';
	public static $singularNoun = 'int fixed record';
	public static $pluralNoun = 'int fixed records';

	public static $indexes = [
		'i01' => ['fields' => ['i01']],
		'i06' => ['fields' => ['i06']],
		'i12' => ['fields' => ['i12']],
	];

	#[Column(type: 'integer', primary: true, autoincrement: true, unsigned: true)]
	protected $int_fixed_id;

	#[Column(type: 'int')] protected $i01;
	#[Column(type: 'int')] protected $i02;
	#[Column(type: 'int')] protected $i03;
	#[Column(type: 'int')] protected $i04;
	#[Column(type: 'int')] protected $i05;
	#[Column(type: 'int')] protected $i06;
	#[Column(type: 'int')] protected $i07;
	#[Column(type: 'int')] protected $i08;
	#[Column(type: 'int')] protected $i09;
	#[Column(type: 'int')] protected $i10;
	#[Column(type: 'int')] protected $i11;
	#[Column(type: 'int')] protected $i12;
	}
