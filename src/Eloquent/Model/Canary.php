<?php

namespace ThePHPBench\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class Canary extends Model
	{
	public $incrementing = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $primaryKey = 'canary_id';

	protected $table = 'canaries';

	protected $casts = [
		'bool_flag' => 'boolean',
		'decimal_value' => 'decimal:4',
		'date_value' => 'date',
		'datetime_value' => 'datetime',
	];
	}
