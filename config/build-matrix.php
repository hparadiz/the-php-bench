<?php

declare(strict_types=1);

use ThePHPBench\Seed\ScenarioRegistry;

if (! \class_exists(ScenarioRegistry::class))
	{
	require_once __DIR__ . '/../src/Seed/FieldDefinition.php';
	require_once __DIR__ . '/../src/Seed/ScenarioDefinition.php';
	require_once __DIR__ . '/../src/Seed/ScenarioRegistry.php';
	}

/**
 * @param array<string, string|int> $base
 * @return list<array<string, mixed>>
 */
function benchmarkScenarioMatrix(array $base, bool $includeSimple = false, int $simplePriority = 0) : array
	{
	$tests = [];

	if ($includeSimple)
		{
		$tests[] = \array_replace($base, [
			'scenario' => 'simple.10.indexed',
			'iterations' => 10,
			'priority' => $simplePriority,
			'description' => 'Simple ' . ($base['description'] ?? ''),
		]);
		}

	$priority = 100;

	foreach (benchmarkScenarioDefinitions() as $scenario)
		{
		$tests[] = \array_replace($base, [
			'scenario' => $scenario['id'],
			'iterations' => $scenario['row_count'],
			'priority' => $priority++,
			'description' => $scenario['label'] . ' ' . ($base['description'] ?? ''),
		]);
		}

	return $tests;
	}

/**
 * @return list<array{id: string, family: string, row_count: int, label: string}>
 */
function benchmarkScenarioDefinitions() : array
	{
	$definitions = [];

	foreach (ScenarioRegistry::all() as $scenario)
		{
		if (! $scenario->indexed || 'simple' === $scenario->modelFamily)
			{
			continue;
			}

		$definitions[] = [
			'id' => $scenario->id,
			'family' => $scenario->modelFamily,
			'row_count' => $scenario->rowCount,
			'label' => benchmarkScenarioLabel($scenario->modelFamily, $scenario->rowCount),
		];
		}

	usort($definitions, static function(array $a, array $b) : int
		{
		$familyCompare = benchmarkFamilyOrder($a['family']) <=> benchmarkFamilyOrder($b['family']);

		return 0 !== $familyCompare ? $familyCompare : ($a['row_count'] <=> $b['row_count']);
		});

	return $definitions;
	}

function benchmarkFamilyLabel(string $family) : string
	{
	return match ($family) {
		'canary' => 'Canary',
		'float_fixed' => 'FloatFixed',
		'int_fixed' => 'IntFixed',
		'string_fixed' => 'StringFixed',
		'string_variable' => 'StringVariable',
		'simple' => 'Simple',
		default => throw new InvalidArgumentException("Unknown scenario family: {$family}"),
	};
	}

function benchmarkFamilyOrder(string $family) : int
	{
	return match ($family) {
		'canary' => 10,
		'float_fixed' => 20,
		'int_fixed' => 30,
		'string_fixed' => 40,
		'string_variable' => 50,
		'simple' => 0,
		default => 999,
	};
	}

function benchmarkScenarioLabel(string $family, int $rowCount) : string
	{
	return benchmarkFamilyLabel($family) . ' ' . $rowCount;
	}
