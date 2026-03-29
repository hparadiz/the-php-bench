<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'db:setup:mysql', description: 'Initialize the local MySQL database/user for benchmarking')]
final class DbSetupMysqlCommand extends Command
	{
	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$statements = [
			"CREATE DATABASE IF NOT EXISTS `php-orm-sql-benchmarks` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",
			"CREATE USER IF NOT EXISTS 'divergence'@'localhost' IDENTIFIED BY 'printcout';",
			"GRANT ALL PRIVILEGES ON `php-orm-sql-benchmarks`.* TO 'divergence'@'localhost';",
			'FLUSH PRIVILEGES;',
		];

		foreach ($statements as $statement)
			{
			$exitCode = ScriptRunner::run(['mysql', '-e', $statement], $output, \dirname(__DIR__, 2));

			if ($exitCode !== 0)
				{
				return Command::FAILURE;
				}
			}

		$output->writeln('Done. MySQL is ready for php-orm-sql-benchmarks.');
		$output->writeln('Database : php-orm-sql-benchmarks');
		$output->writeln('User     : divergence');
		$output->writeln('Password : printcout');
		$output->writeln('Host     : localhost');

		return Command::SUCCESS;
		}
	}
