<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\Build\ManifestRepository;
use ThePHPBench\Build\PharCompiler;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:build', description: 'Build versioned runtime PHARs and optionally select them for use')]
final class RuntimeBuildCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('framework', InputArgument::OPTIONAL, 'Framework/runtime name')
			->addOption('all', null, InputOption::VALUE_NONE, 'Build all runtimes')
			->addOption('select', null, InputOption::VALUE_NONE, 'Select the built PHAR as the active runtime after building');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$framework = (string)($input->getArgument('framework') ?? '');
		$buildAll = (bool)$input->getOption('all');
		$select = (bool)$input->getOption('select');

		if ($buildAll)
			{
			$frameworks = ManifestRepository::knownFrameworks();
			}
		elseif ($framework !== '')
			{
			$this->assertFramework($framework);
			$frameworks = [$framework];
			}
		else
			{
			throw new \RuntimeException('Pass a framework name or use --all.');
			}

		$failures = 0;

		foreach ($frameworks as $name)
			{
			$output->writeln("<options=bold>Building {$name}</>");
			$path = $this->buildFramework($name, $output);

			if ($path === null)
				{
				++$failures;
				continue;
				}

			$output->writeln('<fg=green>built:</> ' . $path);

			if ($select)
				{
				RuntimeRegistry::selectPharForFramework($name, $path);
				$output->writeln('<fg=green>selected:</> ' . \basename($path));
				}
			}

		return $failures === 0 ? Command::SUCCESS : Command::FAILURE;
		}

	private function buildFramework(string $framework, OutputInterface $output) : ?string
		{
		try
			{
			return PharCompiler::build(
				ManifestRepository::runtime($framework),
				RuntimeRegistry::defaultPharPathForFramework($framework)
			);
			}
		catch (\Throwable $exception)
			{
			$output->writeln("<error>Build failed for {$framework}: {$exception->getMessage()}</error>");

			return null;
			}
		}

	private function assertFramework(string $framework) : void
		{
		if (! \in_array($framework, ManifestRepository::knownFrameworks(), true))
			{
			throw new \RuntimeException("Unknown runtime {$framework}");
			}
		}
	}
