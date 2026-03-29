<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\Seed\DeterministicSeeder;
use ThePHPBench\Seed\ScenarioRegistry;
use ThePHPBench\Seed\SqlEmitter;

#[AsCommand(name: 'seed:preview', description: 'Preview generated benchmark seed data for one scenario and backend')]
final class SeedPreviewCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('scenario', InputArgument::REQUIRED, 'Scenario id, e.g. canary.100.indexed')
			->addArgument('backend', InputArgument::REQUIRED, 'Backend: sqlite, mysql, mariadb, or pgsql')
			->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Output format: summary, json, or sql', 'summary')
			->addOption('limit', 'l', InputOption::VALUE_REQUIRED, 'How many rows to preview', '3');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$scenario = ScenarioRegistry::get((string)$input->getArgument('scenario'));
		$backend = (string)$input->getArgument('backend');
		$format = \strtolower((string)$input->getOption('format'));
		$limit = \max(1, (int)$input->getOption('limit'));

		$seeder = new DeterministicSeeder();
		$rows = $seeder->generateRows($scenario, $limit);

		match ($format) {
			'json' => $output->writeln(\json_encode([
				'scenario_id' => $scenario->id,
				'backend' => $backend,
				'table' => $scenario->getTableName(),
				'rows' => $rows,
			], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)),
			'sql' => $output->writeln(\implode("\n\n", (new SqlEmitter())->emit($scenario, $backend, $rows))),
			default => $this->renderSummary($output, $scenario->id, $backend, $scenario->getTableName(), $rows),
		};

		return Command::SUCCESS;
		}

	/**
	 * @param list<array<string, scalar|null>> $rows
	 */
	private function renderSummary(OutputInterface $output, string $scenarioId, string $backend, string $table, array $rows) : void
		{
		$output->writeln("<options=bold>{$scenarioId}</>");
		$output->writeln("backend: {$backend}");
		$output->writeln("table: {$table}");
		$output->writeln('rows:');

		foreach ($rows as $index => $row)
			{
			$output->writeln('  [' . ($index + 1) . '] ' . \json_encode($row, JSON_UNESCAPED_SLASHES));
			}
		}
	}
