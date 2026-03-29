<?php

declare(strict_types=1);

namespace ThePHPBench\Seed;

final class DumpRepository
	{
	public function exists(string $scenarioId, string $backend) : bool
		{
		return \is_file($this->pathFor($scenarioId, $backend));
		}

	public function pathFor(string $scenarioId, string $backend, bool $withRows = true) : string
		{
		$normalized = $this->normalizeBackend($backend);
		$kind = $withRows ? 'full' : 'schema';

		return \dirname(__DIR__, 2) . "/storage/dumps/{$kind}/{$normalized}/{$scenarioId}.sql";
		}

	/**
	 * @return array<int, string>
	 */
	public function readLines(string $scenarioId, string $backend, bool $withRows = true) : array
		{
		$path = $this->pathFor($scenarioId, $backend, $withRows);

		if (! \is_file($path))
			{
			throw new \RuntimeException("SQL dump not found: {$path}");
			}

		$lines = \file($path);

		if (false === $lines)
			{
			throw new \RuntimeException("Unable to read SQL dump: {$path}");
			}

		return $lines;
		}

	public function ensure(ScenarioDefinition $scenario, string $backend, int $insertBatchSize = 250, bool $withRows = true) : string
		{
		$path = $this->pathFor($scenario->id, $backend, $withRows);

		if (\is_file($path))
			{
			return $path;
			}

		$directory = \dirname($path);

		if (! \is_dir($directory))
			{
			\mkdir($directory, 0777, true);
			}

		$rows = $withRows ? (new DeterministicSeeder())->generateRows($scenario) : [];
		$sql = \implode("\n\n", (new SqlEmitter())->emit($scenario, $backend, $rows, $insertBatchSize)) . "\n";

		if (false === \file_put_contents($path, $sql))
			{
			throw new \RuntimeException("Unable to write SQL dump: {$path}");
			}

		return $path;
		}

	private function normalizeBackend(string $backend) : string
		{
		return match (\strtolower($backend)) {
			'postgres', 'postgresql' => 'pgsql',
			default => \strtolower($backend),
		};
		}
	}
