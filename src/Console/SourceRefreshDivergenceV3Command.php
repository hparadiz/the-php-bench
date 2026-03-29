<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'source:refresh-divergence-v3', description: 'Refresh the local DivergenceV3 source mirror from ../framework')]
final class SourceRefreshDivergenceV3Command extends Command
	{
	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$root = \dirname(__DIR__, 2);
		$sourceDir = $root . '/../framework';
		$targetDir = $root . '/local/DivergenceV3';

		if (! \is_dir($sourceDir))
			{
			throw new \RuntimeException("Source directory not found: {$sourceDir}");
			}

		$this->removeTree($targetDir);
		$this->copyTree($sourceDir, $targetDir);

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($targetDir . '/src', \RecursiveDirectoryIterator::SKIP_DOTS)
		);

		foreach ($iterator as $file)
			{
			if (! $file->isFile() || $file->getExtension() !== 'php')
				{
				continue;
				}

			$contents = (string)\file_get_contents($file->getPathname());
			$contents = \str_replace('Divergence\\', 'DivergenceReflections\\', $contents);
			$contents = \str_replace('namespace Divergence;', 'namespace DivergenceReflections;', $contents);
			\file_put_contents($file->getPathname(), $contents);
			}

		$output->writeln("Refreshed {$targetDir} from {$sourceDir}");

		return Command::SUCCESS;
		}

	private function removeTree(string $path) : void
		{
		if (! \file_exists($path))
			{
			return;
			}

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($iterator as $item)
			{
			$item->isDir() ? \rmdir($item->getPathname()) : \unlink($item->getPathname());
			}

		\rmdir($path);
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
