<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\CanonicalModelGenerator;

#[AsCommand(name: 'model:generate-canonical', description: 'Generate canonical benchmark model classes')]
final class ModelGenerateCanonicalCommand extends Command
	{
	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		CanonicalModelGenerator::generate();
		$output->writeln('Generated canonical models for ActiveRecord, Cake, CakeCached, Doctrine, Eloquent, and PHPFUI.');

		return Command::SUCCESS;
		}
	}
