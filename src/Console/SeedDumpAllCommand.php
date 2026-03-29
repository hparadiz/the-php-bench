<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\Seed\DumpRepository;
use ThePHPBench\Seed\ScenarioRegistry;

#[AsCommand(name: 'seed:dump-all', description: 'Generate deterministic SQL dump files for multiple scenarios and backends')]
final class SeedDumpAllCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('backend', InputArgument::OPTIONAL, 'Backend: sqlite, mysql, mariadb, pgsql, or all', 'all')
			->addOption('scenario', 's', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'One or more scenario ids to generate')
			->addOption('insert-batch-size', null, InputOption::VALUE_REQUIRED, 'Rows per SQL INSERT statement', '250');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$requestedBackend = \strtolower((string)$input->getArgument('backend'));
		$backends = 'all' === $requestedBackend ? ['sqlite', 'mysql', 'mariadb', 'pgsql'] : [$requestedBackend];
		$scenarioFilter = (array)$input->getOption('scenario');
		$insertBatchSize = \max(1, (int)$input->getOption('insert-batch-size'));
		$repository = new DumpRepository();
		$scenarios = ScenarioRegistry::all();
		$count = 0;

		foreach ($scenarios as $scenarioId => $scenario)
			{
			if ($scenarioFilter !== [] && ! \in_array($scenarioId, $scenarioFilter, true))
				{
				continue;
				}

			foreach ($backends as $backend)
				{
				$path = $repository->ensure($scenario, $backend, $insertBatchSize);
				++$count;
				$output->writeln("{$backend} {$scenarioId} -> {$path}");
				}
			}

		$output->writeln('');
		$output->writeln("<options=bold>generated {$count} dump file(s)</>");

		return Command::SUCCESS;
		}
	}
