<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:seed-vendors', description: 'Seed isolated runtime vendor directories from existing installs')]
final class RuntimeSeedVendorsCommand extends Command
	{
	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$root = \dirname(__DIR__, 2);
		$sharedFrameworks = [
			'ActiveRecord',
			'Cake',
			'CakeCached',
			'Doctrine',
			'Eloquent',
			'PHPFUI',
			'PHPFUIBatch',
			'Propel2',
			'RedBean',
		];

		foreach ($sharedFrameworks as $framework)
			{
			$this->seedVendor($output, $root . '/vendor', RuntimeRegistry::runtimeDirForFramework($framework) . '/vendor', $framework);
			}

		$this->seedVendor($output, $root . '/vendor', RuntimeRegistry::runtimeDirForFramework('DivergenceV2') . '/vendor', 'DivergenceV2');
		$output->writeln('Skipping DivergenceV3 vendor seeding; use runtime:install-vendors DivergenceV3 for the published package.');

		$output->writeln("Seeded runtime vendors under {$root}/runtimes");

		return Command::SUCCESS;
		}

	private function seedVendor(OutputInterface $output, string $source, string $target, string $framework) : void
		{
		if (! \is_dir($source))
			{
			throw new \RuntimeException("Vendor source missing for {$framework}: {$source}");
			}

		$output->writeln("Seeding {$framework}");
		$this->copyTree($source, $target);
		}

	private function copyTree(string $source, string $target) : void
		{
		if (! \is_dir($target) && ! \mkdir($target, 0777, true) && ! \is_dir($target))
			{
			throw new \RuntimeException("Unable to create {$target}");
			}

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ($iterator as $item)
			{
			$relative = \ltrim(\substr($item->getPathname(), \strlen($source)), '/');
			$destination = $target . ($relative !== '' ? '/' . $relative : '');

			if ($item->isDir())
				{
				if (! \is_dir($destination) && ! \mkdir($destination, 0777, true) && ! \is_dir($destination))
					{
					throw new \RuntimeException("Unable to create {$destination}");
					}

				continue;
				}

			if (! \copy($item->getPathname(), $destination))
				{
				throw new \RuntimeException("Unable to copy {$destination}");
				}
			}
		}
	}
