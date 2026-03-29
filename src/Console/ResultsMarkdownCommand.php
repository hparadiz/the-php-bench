<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ThePHPBench\CSV\FileReader;

#[AsCommand(name: 'results:markdown', description: 'Convert a results CSV file to a Markdown table')]
final class ResultsMarkdownCommand extends Command
	{
	protected function configure() : void
		{
		$this->addArgument('file', InputArgument::REQUIRED, 'Results CSV path');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		$resultsFile = (string)$input->getArgument('file');
		$fileReader = new FileReader($resultsFile);
		$lengths = [];
		$headerRow = null;

		foreach ($fileReader as $row)
			{
			$headerRow ??= \array_combine(\array_keys($row), \array_keys($row));

			foreach ($row as $field => $value)
				{
				$formatted = $this->formatCell($value);
				$lengths[$field] = \max($lengths[$field] ?? 0, \strlen($formatted), \strlen((string)$field));
				}
			}

		if ($headerRow === null)
			{
			throw new \RuntimeException("No rows found in {$resultsFile}");
			}

		$parts = \explode('.', $resultsFile);
		$mdFile = $parts[0] . '.md';
		$outputText = '|' . \implode('|', $this->padRow($headerRow, $lengths)) . "|\n";
		$separator = [];

		foreach ($headerRow as $field => $_label)
			{
			$separator[$field] = \str_repeat('-', $lengths[$field]);
			}

		$outputText .= '|' . \implode('|', $separator) . "|\n";

		foreach ($fileReader as $row)
			{
			$outputText .= '|' . \implode('|', $this->padRow($row, $lengths)) . "|\n";
			}

		if (\file_put_contents($mdFile, $outputText) === false)
			{
			throw new \RuntimeException("Unable to write {$mdFile}");
			}

		$output->writeln($mdFile);

		return Command::SUCCESS;
		}

	private function padRow(array $row, array $lengths) : array
		{
		foreach ($row as $field => $label)
			{
			$value = $this->formatCell($row[$field]);
			$row[$field] = \str_pad($value, $lengths[$field], ' ', \is_numeric($label) ? \STR_PAD_LEFT : \STR_PAD_RIGHT);
			}

		return $row;
		}

	private function formatCell(mixed $value) : string
		{
		$string = (string)$value;

		if (\is_numeric($string) && \str_contains($string, '.'))
			{
			return \number_format((float)$string, 7, '.', '');
			}

		return $string;
		}
	}
