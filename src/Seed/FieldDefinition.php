<?php

declare(strict_types=1);

namespace ThePHPBench\Seed;

final class FieldDefinition
	{
	public function __construct(
		public readonly string $name,
		public readonly string $type,
		public readonly bool $nullable = false,
		public readonly ?int $length = null,
		public readonly ?int $precision = null,
		public readonly ?int $scale = null,
	) {}
	}
