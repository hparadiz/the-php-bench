<?php

namespace ThePHPBench\ActiveRecord\Model;

class StringFixed extends \ActiveRecord\Model
	{
	public static string $table_name = 'string_fixed_records';

	public static string $primary_key = 'string_fixed_id';

	public static string $sequence = 'string_fixed_records_string_fixed_id_seq';
	}
