<?php

namespace ThePHPBench\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class StringVariable extends Model
	{
	public $incrementing = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $primaryKey = 'string_variable_id';

	protected $table = 'string_variable_records';

	protected $casts = [];
	}
