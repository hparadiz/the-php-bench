<?php

namespace ThePHPBench\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class Simple extends Model
	{
	public $incrementing = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $primaryKey = 'simple_id';

	protected $table = 'simple_records';

	protected $casts = [];
	}
