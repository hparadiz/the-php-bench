<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\DisplayName;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:test', description: 'Run the simple smoke test against built runtime PHARs')]
final class RuntimeTestCommand extends Command
	{
	protected function configure() : void
		{
		$this->addOption('no-phar', null, InputOption::VALUE_NONE, 'Run each runtime worker directly instead of using the built PHAR');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$root = \dirname(__DIR__, 2);
		$useDirectWorkers = (bool)$input->getOption('no-phar');
		$config = require $root . '/config/simple.php';
		$tests = \is_array($config['tests'] ?? null) ? $config['tests'] : [];
		$frameworks = [
			'ActiveRecord',
			'Atlas',
			'Cake',
			'CakeCached',
			'Cycle',
			'Doctrine',
			'DivergenceV2',
			'DivergenceV3',
			'Eloquent',
			'PHPFUI',
			'PHPFUIBatch',
			'Propel2',
			'RedBean',
			'Yii',
		];
		$preferredDrivers = [
			'DivergenceV2' => ['mysql'],
			'DivergenceV3' => ['pgsql', 'sqlite', 'mysql'],
			'default' => ['sqlite', 'mysql', 'pgsql'],
		];
		$failures = [];

		$selectTests = static function(string $framework) use ($tests, $preferredDrivers) : array
			{
			$drivers = $preferredDrivers[$framework] ?? $preferredDrivers['default'];
			$selected = [];

			foreach ($drivers as $driver)
				{
				foreach ($tests as $test)
					{
					if (($test['namespace'] ?? null) !== $framework || ($test['driver'] ?? null) !== $driver)
						{
						continue;
						}

					if ($driver === 'sqlite' && ($test['dbname'] ?? null) !== ':memory:')
						{
						continue;
						}

					if ($framework === 'DivergenceV2' && ! \str_contains((string)($test['description'] ?? ''), 'MariaDB'))
						{
						continue;
						}

					$selected[] = $test;
					break;
					}
				}

			if ($selected === [] && $framework === 'DivergenceV2')
				{
				foreach ($tests as $test)
					{
					if (($test['namespace'] ?? null) === $framework && ($test['driver'] ?? null) === 'mysql')
						{
						$selected[] = $test;
						break;
						}
					}
				}

			return $selected;
			};

		foreach ($frameworks as $framework)
			{
			$selectedTests = $selectTests($framework);

			if ($selectedTests === [])
				{
				$displayName = DisplayName::forFramework($framework);
				$failures[] = "{$framework}: no smoke config found";
				$output->writeln("<error>FAIL {$displayName}: no smoke config found</error>");
				continue;
				}

			foreach ($selectedTests as $test)
				{
				$payload = \base64_encode((string)\json_encode([
					'test' => $test,
					'iterations' => (int)($test['iterations'] ?? ($config['iterations'] ?? 10)),
					'runNumber' => 1,
					'totalRuns' => 1,
				]));

				$command = $this->runtimeCommand($framework, $payload, $useDirectWorkers);

				if ($command === null)
					{
					$displayName = DisplayName::forFramework($framework);
					$missing = $useDirectWorkers
						? RuntimeRegistry::runtimeDirForFramework($framework) . '/bin/worker'
						: RuntimeRegistry::pharPathForFramework($framework);
					$label = $useDirectWorkers ? 'worker' : 'PHAR';
					$failures[] = "{$framework}: missing {$missing}";
					$output->writeln("<error>FAIL {$displayName}: missing {$label}</error>");
					continue;
					}

				[$exitCode, $stdout, $stderr] = $this->runProcess($command, $root);

				$stdoutLines = \array_values(\array_filter(\array_map('trim', \explode("\n", $stdout)), static fn(string $line) : bool => $line !== ''));
				$lastLine = $stdoutLines === [] ? '' : $stdoutLines[\count($stdoutLines) - 1];
				$decoded = $lastLine !== '' ? \json_decode($lastLine, true) : null;

				if ($exitCode !== 0 || ! \is_array($decoded) || isset($decoded['skipped']))
					{
					$reason = $stderr !== '' ? \trim($stderr) : ($lastLine !== '' ? $lastLine : 'no result emitted');
					$displayName = DisplayName::forFramework($framework);
					$failures[] = "{$framework}: {$reason}";
					$output->writeln("<error>FAIL {$displayName}: {$reason}</error>");
					continue;
					}

				$description = (string)($decoded['Description'] ?? $test['description'] ?? '');
				$totalTime = (float)($decoded['Total Runtime Time'] ?? 0.0);
				$output->writeln(\sprintf('<info>PASS %s: %s in %.3fs</info>', DisplayName::forFramework($framework), $description, $totalTime));
				}
			}

		if ($failures !== [])
			{
			return Command::FAILURE;
			}

		$output->writeln(\sprintf('All %d runtime PHARs passed.', \count($frameworks)));

		return Command::SUCCESS;
		}

	/**
	 * @return list<string>|null
	 */
	private function runtimeCommand(string $framework, string $payload, bool $useDirectWorkers) : ?array
		{
		if ($useDirectWorkers)
			{
			$worker = RuntimeRegistry::runtimeDirForFramework($framework) . '/bin/worker';

			return \is_file($worker) ? [\PHP_BINARY, $worker, $payload] : null;
			}

		$pharPath = RuntimeRegistry::pharPathForFramework($framework);

		return \is_file($pharPath) ? [\PHP_BINARY, $pharPath, $payload] : null;
		}

	/**
	 * @param list<string> $command
	 * @return array{0: int, 1: string, 2: string}
	 */
	private function runProcess(array $command, string $cwd) : array
		{
		$descriptorSpec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
			2 => ['pipe', 'w'],
		];
		$process = \proc_open($command, $descriptorSpec, $pipes, $cwd);

		if (! \is_resource($process))
			{
			return [1, '', 'unable to start process'];
			}

		\fclose($pipes[0]);
		$stdout = (string)\stream_get_contents($pipes[1]);
		$stderr = (string)\stream_get_contents($pipes[2]);
		\fclose($pipes[1]);
		\fclose($pipes[2]);

		return [\proc_close($process), $stdout, $stderr];
		}
	}
