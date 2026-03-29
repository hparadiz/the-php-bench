<?php

namespace ThePHPBench\ActiveRecord\Model;

class IntFixed extends \ActiveRecord\Model
	{
	public static string $table_name = 'int_fixed_records';

	public static string $primary_key = 'int_fixed_id';

	public static string $sequence = 'int_fixed_records_int_fixed_id_seq';
	}
