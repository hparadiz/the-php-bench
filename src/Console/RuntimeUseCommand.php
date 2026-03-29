<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\ApplicationVersion;
use ThePHPBench\Build\ManifestRepository;
use ThePHPBench\DisplayName;
use ThePHPBench\RuntimeRegistry;

#[AsCommand(name: 'runtime:use', description: 'Select which built PHAR a runtime should use')]
final class RuntimeUseCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('framework', InputArgument::REQUIRED, 'Framework/runtime name')
			->addArgument('selector', InputArgument::REQUIRED, 'A version, PHAR basename, path, `latest`, or `default`');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$framework = (string)$input->getArgument('framework');
		$selector = (string)$input->getArgument('selector');

		$this->assertFramework($framework);

		if (\in_array(\strtolower($selector), ['default', 'auto', 'clear'], true))
			{
			RuntimeRegistry::clearSelectedPharForFramework($framework);
			$output->writeln('<fg=green>Selection cleared.</>');
			$output->writeln('effective: ' . RuntimeRegistry::pharPathForFramework($framework));

			return Command::SUCCESS;
			}

		$path = $this->resolveSelection($framework, $selector);
		RuntimeRegistry::selectPharForFramework($framework, $path);

		$output->writeln('<fg=green>Selected runtime PHAR.</>');
		$output->writeln('framework: ' . DisplayName::forFramework($framework));
		$output->writeln('phar: ' . $path);

		return Command::SUCCESS;
		}

	private function resolveSelection(string $framework, string $selector) : string
		{
		if (\is_file($selector))
			{
			return $selector;
			}

		$distPath = \dirname(__DIR__, 2) . '/dist/' . $selector;

		if (\is_file($distPath))
			{
			return $distPath;
			}

		$available = RuntimeRegistry::availablePharsForFramework($framework);

		if ($available === [])
			{
			throw new \RuntimeException("No built PHARs found for {$framework}");
			}

		if (\strtolower($selector) === 'latest')
			{
			return $available[0]['path'];
			}

		$normalized = ApplicationVersion::normalize($selector);
		$matches = [];

		foreach ($available as $phar)
			{
			if ($phar['basename'] === $selector || $phar['version_slug'] === $normalized)
				{
				$matches[] = $phar['path'];
				}
			}

		if (\count($matches) === 1)
			{
			return $matches[0];
			}

		if (\count($matches) > 1)
			{
			throw new \RuntimeException("Selector {$selector} matched multiple PHARs for {$framework}");
			}

		throw new \RuntimeException("No PHAR matched selector {$selector} for {$framework}");
		}

	private function assertFramework(string $framework) : void
		{
		if (! \in_array($framework, ManifestRepository::knownFrameworks(), true))
			{
			throw new \RuntimeException("Unknown runtime {$framework}");
			}
		}
	}
