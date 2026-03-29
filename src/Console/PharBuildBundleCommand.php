<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\ApplicationVersion;
use ThePHPBench\Build\ManifestRepository;
use ThePHPBench\Build\PharCompiler;

#[AsCommand(name: 'phar:build-bundle', description: 'Build a versioned bundle PHAR')]
final class PharBuildBundleCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('name', InputArgument::OPTIONAL, 'Bundle name', 'full-matrix')
			->addArgument('frameworks', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Framework names to include')
			->addOption('output', null, InputOption::VALUE_REQUIRED, 'Optional output path');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$name = (string)$input->getArgument('name');
		$frameworks = \array_values(\array_filter(\array_map('strval', (array)$input->getArgument('frameworks'))));
		$customOutput = (string)($input->getOption('output') ?? '');
		$target = $customOutput !== ''
			? $customOutput
			: (\dirname(__DIR__, 2) . '/dist/bundle-' . $name . '-' . ApplicationVersion::slug() . '.phar');

		if ($frameworks === [])
			{
			$frameworks = ManifestRepository::knownFrameworks();
			}

		try
			{
			$path = PharCompiler::build(ManifestRepository::bundle($name, $frameworks), $target);
			$output->writeln($path);

			return Command::SUCCESS;
			}
		catch (\Throwable $exception)
			{
			$output->writeln('<error>' . $exception->getMessage() . '</error>');

			return Command::FAILURE;
			}
		}
	}
