<?php

namespace ThePHPBench;

/**
 * @implements \Iterator<int, \ThePHPBench\Configuration>
 */
class Configurations implements \Countable, \Iterator
	{
	private int $iterations = 5000;

	private array $parameters = [];	// @phpstan-ignore-line

	/** @var array<string, string> */
	private array $runMetadata = [];

	private bool $valid = true;

	public function __construct(string $configFile)
		{
		$this->parameters = [];

		if (\file_exists($configFile))
			{
			$this->parameters = include $configFile;

			if (! \is_array($this->parameters))	// @phpstan-ignore-line
				{
				$this->parameters = [];
				}
			}

		if (empty($this->parameters['tests']))
			{
			$source = __DIR__;
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST
			);

			foreach ($iterator as $item)
				{
				if ($item->isFile() && 'Tests.php' == $item->getFilename())
					{
					$fileName = \str_replace('\\', '/', $item->getPathname());
					$parts = \explode('/', $fileName);

					while ('src' != \array_shift($parts))
						{
						}
					$this->parameters['tests'][] = ['namespace' => $parts[0], 'description' => 'sqlite::memory:', 'dbname' => ':memory:', ];
					$this->parameters['tests'][] = ['namespace' => $parts[0], 'description' => 'sqlite file', ];
					}
				}
			}

		$this->iterations = $this->parameters['iterations'] ?? 5000;
		$run = $this->parameters['run'] ?? [];
		$this->runMetadata = \is_array($run) ? \array_filter([
			'type' => (string)($run['type'] ?? ''),
			'label' => (string)($run['label'] ?? ''),
		]) : [];
		$this->rewind();
		}

	public function count() : int
		{
		return \count($this->parameters['tests'] ?? []);
		}

	public function current() : \ThePHPBench\Configuration
		{
		return new \ThePHPBench\Configuration(\current($this->parameters['tests']), $this->iterations);
		}

	public function getIterations() : int
		{
		return $this->iterations;
		}

	public function getRunLabel() : ?string
		{
		return $this->runMetadata['label'] ?? null;
		}

	public function getRunType() : ?string
		{
		return $this->runMetadata['type'] ?? null;
		}

	public function key() : int
		{
		return \key($this->parameters['tests']);
		}

	public function next() : void
		{
		$this->valid = false !== \next($this->parameters['tests']);
		}

	public function rewind() : void
		{
		$this->valid = false !== \reset($this->parameters['tests']);
		}

	public function valid() : bool
		{
		return $this->valid;
		}
	}
