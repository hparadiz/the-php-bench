<?php

namespace ThePHPBench\ActiveRecord\Model;

class FloatFixed extends \ActiveRecord\Model
	{
	public static string $table_name = 'float_fixed_records';

	public static string $primary_key = 'float_fixed_id';

	public static string $sequence = 'float_fixed_records_float_fixed_id_seq';
	}
