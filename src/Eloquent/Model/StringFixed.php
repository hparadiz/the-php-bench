<?php

namespace ThePHPBench\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class StringFixed extends Model
	{
	public $incrementing = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $primaryKey = 'string_fixed_id';

	protected $table = 'string_fixed_records';

	protected $casts = [];
	}
