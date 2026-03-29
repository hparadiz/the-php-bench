<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\ApplicationVersion;
use ThePHPBench\Build\ManifestRepository;
use ThePHPBench\Build\PharCompiler;

#[AsCommand(name: 'phar:build-controller', description: 'Build the versioned controller PHAR')]
final class PharBuildControllerCommand extends Command
	{
	protected function configure() : void
		{
		$this->addArgument('output', InputArgument::OPTIONAL, 'Optional output path');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$customOutput = (string)($input->getArgument('output') ?? '');
		$target = $customOutput !== ''
			? $customOutput
			: (\dirname(__DIR__, 2) . '/dist/the-php-bench-controller-' . ApplicationVersion::slug() . '.phar');

		try
			{
			$path = PharCompiler::build(ManifestRepository::controller(), $target);
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
