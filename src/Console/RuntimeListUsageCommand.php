<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:list-usage', description: 'Show one-line usage examples for each runtime')]
final class RuntimeListUsageCommand extends Command
	{
	protected function configure() : void
		{
		$this->addArgument('framework', InputArgument::OPTIONAL, 'Optional framework name to show a single runtime usage line');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$framework = (string)($input->getArgument('framework') ?? '');
		$runtimes = RuntimeRegistry::availableRuntimes();

		if ($framework !== '')
			{
			foreach ($runtimes as $runtime)
				{
				if ($runtime['name'] === $framework)
					{
					$this->renderTable($output, [$runtime]);

					return Command::SUCCESS;
					}
				}

			$output->writeln('<error>Unknown runtime ' . $framework . '</error>');

			return Command::FAILURE;
			}

		$this->renderTable($output, $runtimes);

		return Command::SUCCESS;
		}

	/**
	 * @param array{
	 *   name: string,
	 *   display_name: string,
	 *   version: string,
	 *   runtime_dir: string
	 * } $runtime
	 */
	private function renderTable(OutputInterface $output, array $runtimes) : void
		{
		$table = new Table($output);
		$table->setHeaders(['Runtime', 'Usage']);

		foreach ($runtimes as $runtime)
			{
			$table->addRow($this->usageRow($runtime));
			}

		$table->render();
		$output->writeln('');
		$output->writeln('--no-phar uses the Work Directory instead of the Built PHAR.');
		}

	/**
	 * @param array{
	 *   name: string,
	 *   display_name: string,
	 *   version: string,
	 *   runtime_dir: string
	 * } $runtime
	 * @return array{0: string, 1: string}
	 */
	private function usageRow(array $runtime) : array
		{
		$label = $runtime['display_name'] . ($runtime['version'] !== '' ? ' ' . $runtime['version'] : '');
		$command = './bin/the-php-bench benchmark ' . $runtime['name'] . ' --batch-sizes=10 --runs=1 --concurrency=1';

		return [
			$label,
			$command,
		];
		}
	}
