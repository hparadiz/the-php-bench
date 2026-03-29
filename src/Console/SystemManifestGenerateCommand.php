<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\MachineInfo;

#[AsCommand(name: 'system:manifest:generate', description: 'Generate the cached system manifest used for benchmark provenance')]
final class SystemManifestGenerateCommand extends Command
	{
	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$data = MachineInfo::generateManifest();

		if ((string)($data['machine_memory_modules'] ?? '') === '')
			{
			$output->writeln('<error>machine_memory_modules is still empty after dmidecode parsing.</error>');
			$output->writeln('<error>Fix the dmidecode capture path before benchmarking.</error>');

			return 2;
			}

		try
			{
			MachineInfo::writeManifest();
			$output->writeln('Wrote ' . MachineInfo::manifestPath());
			$output->writeln((string)\json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));

			return Command::SUCCESS;
			}
		catch (\Throwable $exception)
			{
			$output->writeln('<error>' . $exception->getMessage() . '</error>');

			return Command::FAILURE;
			}
		}
	}
