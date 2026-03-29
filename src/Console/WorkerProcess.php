<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use ThePHPBench\Configuration;
use ThePHPBench\DisplayName;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Manages the lifecycle of a single runtime worker subprocess via ReactPHP.
 *
 * - Spawns the runtime worker PHAR entrypoint with one encoded payload
 * - Streams stderr live to the console output
 * - Captures the final JSON line from stdout as the result
 * - Tracks wall-clock time from spawn to exit
 * - Resolves a deferred result when the process closes
 */
class WorkerProcess
	{
	private float $startTime = 0.0;

	/** @var array<string,mixed>|null */
	private ?array $result = null;

	private string $stdoutBuffer = '';

	private ?\Closure $onDone = null;

	public function __construct(
		private readonly LoopInterface   $loop,
		private readonly OutputInterface $output,
		private readonly string          $workerBin,
		private readonly Configuration   $config,
		private readonly array           $runMetadata,
		private readonly int             $index,
		private readonly int             $total,
		private readonly int             $runNumber,
		private readonly int             $totalRuns,
	) {}

	/**
	 * Spawn the process.
	 * Calls $onDone(array $result|null, bool $failed) when the process exits.
	 * $result is null on failure or skip.
	 */
	public function spawn(callable $onDone) : void
		{
		$this->onDone    = \Closure::fromCallable($onDone);
		$this->startTime = microtime(true);

		$payload = base64_encode(json_encode([
			'test'       => \array_replace($this->config->getRaw(), $this->runMetadata),
			'iterations' => $this->config->getIterations(),
			'runNumber'  => $this->runNumber,
			'totalRuns'  => $this->totalRuns,
		]));

		$cmd     = PHP_BINARY . ' ' . escapeshellarg($this->workerBin) . ' ' . escapeshellarg($payload);
		$process = new Process($cmd);

		$process->start($this->loop);

		$label = DisplayName::forFramework($this->config->getNamespace()) . ' → ' . $this->config->getDescription() . $this->getRunLabel();
		$this->output->writeln(
			sprintf('<fg=gray>[%d/%d]</> <options=bold>%s</>', $this->index + 1, $this->total, $label)
		);

		$process->stdout->on('data', fn(string $chunk) => $this->onStdout($chunk));
		$process->stderr->on('data', fn(string $chunk) => $this->onStderr($chunk));
		$process->on('exit', fn(?int $code, ?string $signal) => $this->onExit($code, $signal));
		}

	private function onStdout(string $chunk) : void
		{
		$this->stdoutBuffer .= $chunk;

		// parse complete newline-terminated JSON lines as they arrive
		while (($pos = strpos($this->stdoutBuffer, "\n")) !== false)
			{
			$line               = substr($this->stdoutBuffer, 0, $pos);
			$this->stdoutBuffer = substr($this->stdoutBuffer, $pos + 1);

			if ($line === '')
				{
				continue;
				}

			$decoded = json_decode($line, true);

			if (is_array($decoded))
				{
				$this->result = $decoded;
				}
			}
		}

	private function onStderr(string $chunk) : void
		{
		// Stream worker progress lines live, trimming trailing newlines
		foreach (explode("\n", rtrim($chunk, "\n")) as $line)
			{
			if ($line !== '')
				{
				$this->output->writeln('  <fg=gray>│</> ' . $line);
				}
			}
		}

	private function onExit(?int $code, ?string $signal) : void
		{
		$elapsed = round(microtime(true) - $this->startTime, 3);
		$label   = DisplayName::forFramework($this->config->getNamespace()) . ' → ' . $this->config->getDescription() . $this->getRunLabel();

		if ($signal !== null)
			{
			$this->output->writeln(
				"  <fg=red>✗ {$label} killed by signal {$signal}</>"
			);
			($this->onDone)(null, true);

			return;
			}

		if ($code !== 0)
			{
			$this->output->writeln(
				"  <fg=red>✗ {$label} exited with code {$code}</>"
			);
			($this->onDone)(null, true);

			return;
			}

		if ($this->result !== null && isset($this->result['skipped']))
			{
			$this->output->writeln(
				"  <fg=yellow>⊘ skipped — driver not supported</>"
			);
			($this->onDone)(null, false);

			return;
			}

		if ($this->result === null)
			{
			$this->output->writeln(
				"  <fg=red>✗ {$label} exited before emitting a result</>"
			);
			($this->onDone)(null, true);

			return;
			}

		$this->output->writeln(
			sprintf('  <fg=green>✓ done in %ss</>', $elapsed)
		);

		($this->onDone)($this->result, false);
		}

	private function getRunLabel() : string
		{
		$warmup = 1 === $this->runNumber ? ' warmup' : '';

		return sprintf(' [run %d/%d%s]', $this->runNumber, $this->totalRuns, $warmup);
		}
	}
