<?php

declare(strict_types=1);

namespace ThePHPBench\Benchmark;

final class OperationRegistry
	{
	/**
	 * @return list<OperationPattern>
	 */
	public static function readPatterns(int $iterations) : array
		{
		return [
			new OperationPattern('Read All', 'read', self::range(1, $iterations)),
			new OperationPattern('Read Rand 1', 'read', self::randomSelection($iterations, 1)),
			new OperationPattern('Read Rand 10', 'read', self::randomSelection($iterations, 10)),
			new OperationPattern('Read Middle 10', 'read', self::middleSelection($iterations, 10)),
			new OperationPattern('Read Head 10', 'read', self::range(1, \min(10, $iterations))),
			new OperationPattern('Read Tail 10', 'read', self::tailSelection($iterations, 10)),
		];
		}

	/**
	 * @return list<OperationPattern>
	 */
	public static function updatePatterns(int $iterations) : array
		{
		return [
			new OperationPattern('Update All', 'update', self::range(1, $iterations)),
			new OperationPattern('Update Rand 1', 'update', self::randomSelection($iterations, 1)),
			new OperationPattern('Update Rand 10', 'update', self::randomSelection($iterations, 10)),
			new OperationPattern('Update Middle 10', 'update', self::middleSelection($iterations, 10)),
			new OperationPattern('Update Head 10', 'update', self::range(1, \min(10, $iterations))),
			new OperationPattern('Update Tail 10', 'update', self::tailSelection($iterations, 10)),
		];
		}

	/**
	 * @return list<int>
	 */
	private static function middleSelection(int $iterations, int $count) : array
		{
		$count = \min($count, $iterations);

		if ($count <= 0)
			{
			return [];
			}

		$start = \max(1, (int)\floor(($iterations - $count) / 2) + 1);

		return self::range($start, $start + $count - 1);
		}

	/**
	 * @return list<int>
	 */
	private static function randomSelection(int $iterations, int $count) : array
		{
		$count = \min($count, $iterations);
		$pool = self::range(1, $iterations);
		\shuffle($pool);

		return \array_values(\array_slice($pool, 0, $count));
		}

	/**
	 * @return list<int>
	 */
	private static function range(int $start, int $end) : array
		{
		if ($end < $start)
			{
			return [];
			}

		return \array_values(\range($start, $end));
		}

	/**
	 * @return list<int>
	 */
	private static function tailSelection(int $iterations, int $count) : array
		{
		$count = \min($count, $iterations);

		return self::range(\max(1, $iterations - $count + 1), $iterations);
		}
	}
