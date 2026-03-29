<?php

declare(strict_types=1);

namespace ThePHPBench;

final class ApplicationVersion
	{
	public const VERSION = '2.0.0';

	public static function slug() : string
		{
		return self::normalize(self::VERSION);
		}

	public static function normalize(string $value) : string
		{
		$value = \strtolower(\trim($value));
		$value = \preg_replace('/[^a-z0-9]+/', '-', $value) ?? 'unknown';

		return \trim($value, '-') ?: 'unknown';
		}
	}
