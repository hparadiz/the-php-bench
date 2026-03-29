<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\Build\ManifestRepository;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:list', description: 'List runtime metadata, built PHARs, and selected runtime artifacts')]
final class RuntimeListCommand extends Command
	{
	protected function configure() : void
		{
		$this->addArgument('framework', InputArgument::OPTIONAL, 'Optional framework name to show detailed PHARs');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$framework = (string)($input->getArgument('framework') ?? '');

		if ($framework !== '')
			{
			$this->assertFramework($framework);
			$this->renderFramework($output, $framework);

			return Command::SUCCESS;
			}

		$runtimes = RuntimeRegistry::availableRuntimes();

		$output->writeln('<options=bold>Runtime Work Dirs</>');
		$workdirTable = new Table($output);
		$workdirTable->setHeaders(['Runtime', 'Version', 'Work Dir', 'Worker', 'Packages']);

		foreach ($runtimes as $runtime)
			{
			$workerPath = $runtime['runtime_dir'] . '/bin/worker';
			$workdirTable->addRow([
				$runtime['display_name'],
				$runtime['version'],
				$runtime['runtime_dir'],
				\is_file($workerPath) ? 'bin/worker' : '-',
				(string)$runtime['package_count'],
			]);
			}

		$workdirTable->render();
		$output->writeln('');

		$output->writeln('<options=bold>Runtime PHARs</>');
		$table = new Table($output);
		$table->setHeaders(['Runtime', 'Version', 'Selected', 'Effective', 'Built', 'Homepage']);

		foreach ($runtimes as $runtime)
			{
			$table->addRow([
				$runtime['display_name'],
				$runtime['version'],
				$runtime['selected_phar_path'] !== '' ? \basename($runtime['selected_phar_path']) : '-',
				\basename($runtime['phar_path']),
				(string)\count(RuntimeRegistry::availablePharsForFramework($runtime['name'])),
				$runtime['homepage'] !== '' ? $runtime['homepage'] : '-',
			]);
			}

		$table->render();

		return Command::SUCCESS;
		}

	private function renderFramework(OutputInterface $output, string $framework) : void
		{
		$runtime = null;

		foreach (RuntimeRegistry::availableRuntimes() as $entry)
			{
			if ($entry['name'] === $framework)
				{
				$runtime = $entry;
				break;
				}
			}

		if ($runtime === null)
			{
			throw new \RuntimeException("Unknown runtime {$framework}");
			}

		$output->writeln("<options=bold>{$runtime['display_name']}</>");
		$output->writeln('version: ' . ($runtime['version'] !== '' ? $runtime['version'] : 'unknown'));
		$output->writeln('composer: ' . ($runtime['composer_name'] !== '' ? $runtime['composer_name'] : '-'));
		$output->writeln('work dir: ' . $runtime['runtime_dir']);
		$output->writeln('worker: ' . (\is_file($runtime['runtime_dir'] . '/bin/worker') ? $runtime['runtime_dir'] . '/bin/worker' : '-'));
		$output->writeln('packages: ' . $runtime['package_count']);
		$output->writeln('default: ' . \basename($runtime['default_phar_path']));
		$output->writeln('selected: ' . ($runtime['selected_phar_path'] !== '' ? \basename($runtime['selected_phar_path']) : '-'));
		$output->writeln('effective: ' . \basename($runtime['phar_path']));
		$output->writeln('');

		$rows = [];

		foreach (RuntimeRegistry::availablePharsForFramework($framework) as $phar)
			{
			$state = [];

			if ($phar['selected'])
				{
				$state[] = 'selected';
				}

			if ($phar['default'])
				{
				$state[] = 'default';
				}

			$rows[] = [
				$state !== [] ? \implode(', ', $state) : '-',
				$phar['built_at'] > 0 ? \date('Y-m-d H:i:s', $phar['built_at']) : '-',
				$phar['basename'],
			];
			}

		if ($rows === [])
			{
			$output->writeln('<comment>No built PHARs found for this runtime.</comment>');

			return;
			}

		$table = new Table($output);
		$table->setHeaders(['State', 'Built At', 'PHAR']);
		$table->setRows($rows);
		$table->render();
		}

	private function assertFramework(string $framework) : void
		{
		if (! \in_array($framework, ManifestRepository::knownFrameworks(), true))
			{
			throw new \RuntimeException("Unknown runtime {$framework}");
			}
		}
	}
