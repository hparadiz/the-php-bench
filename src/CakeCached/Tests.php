<?php

namespace ThePHPBench\CakeCached;

class Tests extends \ThePHPBench\Cake\Tests
	{
	protected function getTableClassName() : string
		{
		return '\\ThePHPBench\\CakeCached\\Table\\' . $this->scenarioClass;
		}

	/**
	 * Initialize the orm
	 *
	 * @param array<string> $lines sql to import into schema
	 */
	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		parent::init($config, $lines, $runTimer);

		return $this;
		}
	}
