<?php

namespace ThePHPBench\ActiveRecord\Model;

class StringVariable extends \ActiveRecord\Model
	{
	public static string $table_name = 'string_variable_records';

	public static string $primary_key = 'string_variable_id';

	public static string $sequence = 'string_variable_records_string_variable_id_seq';
	}
