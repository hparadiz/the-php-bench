<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Output\OutputInterface;

final class ScriptRunner
	{
	/**
	 * @param list<string> $command
	 */
	public static function run(array $command, OutputInterface $output, ?string $cwd = null) : int
		{
		$descriptorSpec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
			2 => ['pipe', 'w'],
		];

		$process = \proc_open($command, $descriptorSpec, $pipes, $cwd);

		if (! \is_resource($process))
			{
			throw new \RuntimeException('Unable to start process');
			}

		\fclose($pipes[0]);
		$stdout = (string)\stream_get_contents($pipes[1]);
		$stderr = (string)\stream_get_contents($pipes[2]);
		\fclose($pipes[1]);
		\fclose($pipes[2]);
		$exitCode = \proc_close($process);

		if ($stdout !== '')
			{
			$output->write($stdout);
			}

		if ($stderr !== '')
			{
			$output->write($stderr);
			}

		return (int)$exitCode;
		}
	}
