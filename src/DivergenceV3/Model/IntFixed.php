<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV3\Model;

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
	protected ?int $int_fixed_id = null;

	#[Column(type: 'int')] protected int $i01;
	#[Column(type: 'int')] protected int $i02;
	#[Column(type: 'int')] protected int $i03;
	#[Column(type: 'int')] protected int $i04;
	#[Column(type: 'int')] protected int $i05;
	#[Column(type: 'int')] protected int $i06;
	#[Column(type: 'int')] protected int $i07;
	#[Column(type: 'int')] protected int $i08;
	#[Column(type: 'int')] protected int $i09;
	#[Column(type: 'int')] protected int $i10;
	#[Column(type: 'int')] protected int $i11;
	#[Column(type: 'int')] protected int $i12;
	}
