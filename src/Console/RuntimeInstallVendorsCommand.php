<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\Build\ManifestRepository;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:install-vendors', description: 'Install Composer dependencies for isolated runtime projects')]
final class RuntimeInstallVendorsCommand extends Command
	{
	protected function configure() : void
		{
		$this->addArgument('framework', InputArgument::OPTIONAL, 'Optional framework/runtime name');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$framework = (string)($input->getArgument('framework') ?? '');
		$frameworks = [];

		if ($framework !== '')
			{
			if (! \in_array($framework, ManifestRepository::knownFrameworks(), true))
				{
				throw new \RuntimeException("Unknown runtime {$framework}");
				}

			$frameworks[] = $framework;
			}
		else
			{
			$frameworks = ManifestRepository::knownFrameworks();
			}

		$status = Command::SUCCESS;

		foreach ($frameworks as $runtime)
			{
			$output->writeln("<options=bold>Installing vendors for {$runtime}</>");
			$exitCode = ScriptRunner::run([
				'composer',
				'install',
				'--no-interaction',
				'--prefer-dist',
				'--working-dir',
				'runtimes/' . RuntimeRegistry::runtimeDirNameForFramework($runtime),
			], $output, \dirname(__DIR__, 2));

			if ($exitCode !== 0)
				{
				$status = Command::FAILURE;
				}
			}

		return $status;
		}
	}
