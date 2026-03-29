<?php

declare(strict_types=1);

namespace ThePHPBench\Seed;

final class DeterministicSeeder
	{
	/**
	 * @return list<array<string, scalar|null>>
	 */
	public function generateRows(ScenarioDefinition $scenario, ?int $limit = null) : array
		{
		$rows = [];
		$max = \min($scenario->rowCount, $limit ?? $scenario->rowCount);

		for ($rowNumber = 1; $rowNumber <= $max; ++$rowNumber)
			{
			$rows[] = $this->generateRow($scenario, $rowNumber);
			}

		return $rows;
		}

	/**
	 * @return array<string, scalar|null>
	 */
	public function generateRow(ScenarioDefinition $scenario, int $rowNumber) : array
		{
		$row = [];

		foreach ($scenario->fields as $field)
			{
			$row[$field->name] = $this->valueFor($scenario->modelFamily, $field, $rowNumber);
			}

		return $row;
		}

	private function valueFor(string $modelFamily, FieldDefinition $field, int $rowNumber) : string|int|float|bool|null
		{
		if ('id' === $field->type)
			{
			return $rowNumber;
			}

		if ($field->nullable && 0 === $rowNumber % 5)
			{
			return null;
			}

		return match ($modelFamily) {
			'simple' => $this->simpleValue($field, $rowNumber),
			'canary' => $this->canaryValue($field, $rowNumber),
			'int_fixed' => $this->intFixedValue($field, $rowNumber),
			'float_fixed' => $this->floatFixedValue($field, $rowNumber),
			'string_fixed' => $this->stringFixedValue($field, $rowNumber),
			'string_variable' => $this->stringVariableValue($field, $rowNumber),
			default => throw new \InvalidArgumentException("Unhandled model family: {$modelFamily}"),
		};
		}

	private function simpleValue(FieldDefinition $field, int $rowNumber) : string
		{
		return match ($field->name) {
			'title' => "Title {$rowNumber}",
			default => throw new \InvalidArgumentException("Unhandled Simple field: {$field->name}"),
		};
		}

	private function canaryValue(FieldDefinition $field, int $rowNumber) : string|int|float|bool|null
		{
		return match ($field->name) {
			'bool_flag' => true,
			'small_int' => $rowNumber * 2,
			'int_value' => $rowNumber * 100,
			'big_int' => $rowNumber * 100000,
			'float_value' => $rowNumber + 0.25,
			'double_value' => $rowNumber * 10.0 + 0.875,
			'decimal_value' => \sprintf('%d.%04d', $rowNumber, $rowNumber % 10000),
			'fixed_char_8' => $this->fixedString('C8' . $rowNumber, 8),
			'fixed_char_16' => $this->fixedString('C16' . $rowNumber, 16),
			'string_short' => "short-{$rowNumber}",
			'string_medium' => "medium-field-{$rowNumber}-" . \str_repeat('m', ($rowNumber % 10) + 8),
			'string_long' => "long-field-{$rowNumber}-" . \str_repeat('L', 40 + ($rowNumber % 20)),
			'text_value' => "text-block-{$rowNumber} " . \str_repeat('t', 80 + ($rowNumber % 40)),
			'date_value' => $this->dateOffset($rowNumber),
			'datetime_value' => $this->dateTimeOffset($rowNumber),
			'nullable_string' => "nullable-{$rowNumber}",
			'nullable_int' => $rowNumber * 7,
			default => throw new \InvalidArgumentException("Unhandled Canary field: {$field->name}"),
		};
		}

	private function intFixedValue(FieldDefinition $field, int $rowNumber) : int
		{
		$offset = (int)\substr($field->name, 1);

		return $rowNumber * (10 ** (($offset - 1) % 3));
		}

	private function floatFixedValue(FieldDefinition $field, int $rowNumber) : string|float
		{
		return match ($field->name) {
			'f01' => $rowNumber + 0.25,
			'f02' => $rowNumber * 1.5 + 0.125,
			'd01' => $rowNumber / 3.0,
			'd02' => $rowNumber * 10.0 + 0.875,
			'n01' => \sprintf('%d.%02d', $rowNumber, $rowNumber % 100),
			'n02' => \sprintf('%d.%04d', $rowNumber * 2, $rowNumber % 10000),
			'n03' => \sprintf('%d.%06d', $rowNumber * 3, $rowNumber % 1000000),
			'n04' => \sprintf('%d.%08d', $rowNumber * 4, $rowNumber % 100000000),
			default => throw new \InvalidArgumentException("Unhandled FloatFixed field: {$field->name}"),
		};
		}

	private function stringFixedValue(FieldDefinition $field, int $rowNumber) : string
		{
		$prefix = \strtoupper(\str_replace('_', '', $field->name));

		return $this->fixedString($prefix . $rowNumber, $field->length ?? 16);
		}

	private function stringVariableValue(FieldDefinition $field, int $rowNumber) : string
		{
		return match ($field->name) {
			'name' => "Name {$rowNumber}",
			'title' => "Title {$rowNumber} " . \str_repeat('T', $rowNumber % 8),
			'email' => "user{$rowNumber}@example.test",
			'company' => "Company {$rowNumber}",
			'city' => "City {$rowNumber}",
			'region' => "Region {$rowNumber}",
			'postal_code' => \sprintf('%05d', $rowNumber % 100000),
			'country' => 'Country ' . (($rowNumber % 20) + 1),
			'phone' => \sprintf('555-%04d', $rowNumber % 10000),
			'notes' => "Notes {$rowNumber} " . \str_repeat('n', 40 + (($rowNumber % 5) * 20)),
			default => throw new \InvalidArgumentException("Unhandled StringVariable field: {$field->name}"),
		};
		}

	private function fixedString(string $value, int $length) : string
		{
		return \substr(\str_pad($value, $length, 'X'), 0, $length);
		}

	private function dateOffset(int $rowNumber) : string
		{
		return (new \DateTimeImmutable('2020-01-01'))->modify("+{$rowNumber} days")->format('Y-m-d');
		}

	private function dateTimeOffset(int $rowNumber) : string
		{
		return (new \DateTimeImmutable('2020-01-01 00:00:00'))->modify('+' . ($rowNumber * 90) . ' minutes')->format('Y-m-d H:i:s');
		}
	}
