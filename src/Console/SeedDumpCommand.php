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

#[AsCommand(name: 'seed:dump', description: 'Generate a deterministic SQL dump file for one scenario and backend')]
final class SeedDumpCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('scenario', InputArgument::REQUIRED, 'Scenario id, e.g. canary.100.indexed')
			->addArgument('backend', InputArgument::REQUIRED, 'Backend: sqlite, mysql, mariadb, or pgsql')
			->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Output file path')
			->addOption('insert-batch-size', null, InputOption::VALUE_REQUIRED, 'Rows per SQL INSERT statement', '250');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$scenario = ScenarioRegistry::get((string)$input->getArgument('scenario'));
		$backend = \strtolower((string)$input->getArgument('backend'));
		$insertBatchSize = \max(1, (int)$input->getOption('insert-batch-size'));
		$repository = new DumpRepository();
		$outputFile = (string)($input->getOption('output') ?: $repository->pathFor($scenario->id, $backend));
		$writtenPath = $repository->ensure($scenario, $backend, $insertBatchSize);

		if ($writtenPath !== $outputFile)
			{
			$directory = \dirname($outputFile);

			if (! \is_dir($directory))
				{
				\mkdir($directory, 0777, true);
				}

			\copy($writtenPath, $outputFile);
			}

		$output->writeln("<options=bold>dump written</>");
		$output->writeln("scenario: {$scenario->id}");
		$output->writeln("backend: {$backend}");
		$output->writeln("rows: {$scenario->rowCount}");
		$output->writeln("insert batch size: {$insertBatchSize}");
		$output->writeln("file: {$outputFile}");

		return Command::SUCCESS;
		}
	}
