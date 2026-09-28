<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'source:export-divergence-v3-runtime', description: 'Export the staged DivergenceV3 runtime package from ../framework')]
final class SourceExportDivergenceV3RuntimeCommand extends Command
	{
	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$root = \dirname(__DIR__, 2);
		$sourceDir = $root . '/../framework';
		$targetDir = $root . '/local/DivergenceV3Runtime';

		if (! \is_dir($sourceDir))
			{
			throw new \RuntimeException("Source directory not found: {$sourceDir}");
			}

		$tree = $this->runProcess(['git', 'write-tree'], $sourceDir);
		$archivePath = \sys_get_temp_dir() . '/divergence-v3-' . \bin2hex(\random_bytes(8)) . '.tar';

		try
			{
			$this->runProcess(['git', 'archive', '--format=tar', '--output=' . $archivePath, $tree], $sourceDir);
			$this->removeTree($targetDir);

			if (! \mkdir($targetDir, 0777, true) && ! \is_dir($targetDir))
				{
				throw new \RuntimeException("Unable to create {$targetDir}");
				}

			$archive = new \PharData($archivePath);
			$archive->extractTo($targetDir, null, true);
			unset($archive);
			}
		finally
			{
			if (\is_file($archivePath))
				{
				\unlink($archivePath);
				}
			}

		$output->writeln("Exported {$targetDir} from staged tree " . \substr($tree, 0, 8));

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

	/**
	 * @param list<string> $command
	 */
	private function runProcess(array $command, string $workingDirectory) : string
		{
		$process = \proc_open(
			$command,
			[
				['pipe', 'r'],
				['pipe', 'w'],
				['pipe', 'w'],
			],
			$pipes,
			$workingDirectory
		);

		if (! \is_resource($process))
			{
			throw new \RuntimeException('Unable to start git.');
			}

		\fclose($pipes[0]);
		$stdout = (string)\stream_get_contents($pipes[1]);
		$stderr = (string)\stream_get_contents($pipes[2]);
		\fclose($pipes[1]);
		\fclose($pipes[2]);
		$exitCode = \proc_close($process);

		if ($exitCode !== 0)
			{
			throw new \RuntimeException(\trim($stderr) ?: 'Git command failed.');
			}

		return \trim($stdout);
		}
	}
