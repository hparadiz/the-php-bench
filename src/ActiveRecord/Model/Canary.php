<?php

namespace ThePHPBench\ActiveRecord\Model;

class Canary extends \ActiveRecord\Model
	{
	public static string $table_name = 'canaries';

	public static string $primary_key = 'canary_id';

	public static string $sequence = 'canaries_canary_id_seq';
	}
