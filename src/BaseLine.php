<?php

namespace ThePHPBench;

class BaseLine
	{
	private int $memory = 0;

	private int $pauseMemory = 0;

	private float $pauseSeconds = 0.0;

	private float $pauseStartedAt = 0.0;

	private float $startedAt = 0.0;

	public function __construct()
		{
		$this->memory = \memory_get_usage();
		$this->startedAt = \microtime(true);
		}

	public function getMemory() : int
		{
		return \memory_get_usage() - $this->memory - $this->pauseMemory;
		}

	public function pause() : static
		{
		$this->pauseMemory = \memory_get_usage();
		$this->pauseStartedAt = \microtime(true);

		return $this;
		}

	public function resume() : static
		{
		$this->pauseSeconds += \max(0.0, \microtime(true) - $this->pauseStartedAt);
		$this->pauseMemory = \memory_get_usage() - $this->pauseMemory;
		$this->pauseStartedAt = 0.0;

		return $this;
		}

	public function stop() : float
		{
		return \max(0.0, \microtime(true) - $this->startedAt - $this->pauseSeconds);
		}
	}
