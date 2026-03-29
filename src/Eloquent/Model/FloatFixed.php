<?php

namespace ThePHPBench\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class FloatFixed extends Model
	{
	public $incrementing = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $primaryKey = 'float_fixed_id';

	protected $table = 'float_fixed_records';

	protected $casts = [
		'n01' => 'decimal:2',
		'n02' => 'decimal:4',
		'n03' => 'decimal:6',
		'n04' => 'decimal:8',
	];
	}
