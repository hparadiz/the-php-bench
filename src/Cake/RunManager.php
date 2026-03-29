<?php

namespace ThePHPBench\Cake;

class RunManager
	{
	private static string $run = 'default';

	private static int $runNumber = 1;

	public static function get() : string
		{
		$run = self::$run;
		self::$run = 'run' . ++self::$runNumber;

		return $run;
		}
	}
