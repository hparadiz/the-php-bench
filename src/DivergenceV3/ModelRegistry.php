<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV3;

final class ModelRegistry
	{
	public static function classForFamily(string $family) : string
		{
		return match ($family) {
			'canary' => Model\Canary::class,
			'int_fixed' => Model\IntFixed::class,
			'float_fixed' => Model\FloatFixed::class,
			'string_fixed' => Model\StringFixed::class,
			'string_variable' => Model\StringVariable::class,
			default => throw new \InvalidArgumentException("Unknown DivergenceV3 model family: {$family}"),
		};
		}
	}
