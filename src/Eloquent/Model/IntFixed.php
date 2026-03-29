<?php

namespace ThePHPBench\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class IntFixed extends Model
	{
	public $incrementing = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $primaryKey = 'int_fixed_id';

	protected $table = 'int_fixed_records';

	protected $casts = [];
	}
