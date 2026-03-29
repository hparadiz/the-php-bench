<?php

declare(strict_types=1);

namespace ThePHPBench;

final class BackendLabel
	{
	/**
	 * @param array<string, mixed> $row
	 */
	public static function fromRow(array $row) : string
		{
		$backend = \trim((string)($row['Backend'] ?? ''));

		if ($backend !== '')
			{
			return $backend;
			}

		$driver = \trim((string)($row['Driver'] ?? ''));

		if ($driver !== '')
			{
			return self::forDriver($driver, (string)($row['Description'] ?? ''));
			}

		return self::fromDescription((string)($row['Description'] ?? ''), $row);
		}

	public static function forDriver(string $driver, string $description = '') : string
		{
		return match (\strtolower($driver)) {
			'sqlite' => self::sqliteLabel($description),
			'mysql' => self::mysqlLabel($description),
			'mariadb' => 'MariaDB',
			'pgsql', 'postgres', 'postgresql' => 'PostgreSQL',
			default => self::fromDescription($description),
		};
		}

	/**
	 * @param array<string, mixed> $row
	 */
	public static function fromDescription(string $description, array $row = []) : string
		{
		$lower = \strtolower($description);

		if (\str_contains($lower, 'memory'))
			{
			return 'sqlite::memory:';
			}

		if (\str_contains($lower, 'sqlite'))
			{
			return 'sqlite::file:';
			}

		if (\str_contains($lower, 'postgres') || \str_contains($lower, 'pgsql'))
			{
			return 'PostgreSQL';
			}

		if (\str_contains($lower, 'mariadb'))
			{
			return 'MariaDB';
			}

		if (\str_contains($lower, 'mysql'))
			{
			return 'MySQL';
			}

		$scenarioLabel = \trim((string)($row['Scenario Label'] ?? ''));
		$scenarioRows = \trim((string)($row['Scenario Rows'] ?? ''));
		$candidates = \array_filter([
			$scenarioLabel,
			$scenarioRows !== '' ? \trim($scenarioLabel . ' ' . $scenarioRows) : '',
		]);

		foreach ($candidates as $candidate)
			{
			if ($candidate !== '' && \str_starts_with($description, $candidate . ' '))
				{
				return \substr($description, \strlen($candidate . ' '));
				}
			}

		return $description !== '' ? $description : 'Unknown Backend';
		}

	private static function sqliteLabel(string $description) : string
		{
		return \str_contains(\strtolower($description), 'memory')
			? 'sqlite::memory:'
			: 'sqlite::file:';
		}

	private static function mysqlLabel(string $description) : string
		{
		return \str_contains(\strtolower($description), 'maria')
			? 'MariaDB'
			: 'MySQL';
		}
	}
