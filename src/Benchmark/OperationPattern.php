<?php

declare(strict_types=1);

namespace ThePHPBench\Benchmark;

final class OperationPattern
	{
	/**
	 * @param list<int> $logicalIds
	 */
	public function __construct(
		public readonly string $label,
		public readonly string $kind,
		public readonly array $logicalIds,
	) {}
	}
