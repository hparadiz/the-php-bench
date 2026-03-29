<?php

namespace ThePHPBench\ActiveRecord\Model;

class Simple extends \ActiveRecord\Model
	{
	public static string $table_name = 'simple_records';

	public static string $primary_key = 'simple_id';

	public static string $sequence = 'simple_records_simple_id_seq';
	}
