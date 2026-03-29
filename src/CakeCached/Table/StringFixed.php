<?php

namespace ThePHPBench\CakeCached\Table;

class StringFixed extends \Cake\ORM\Table
	{
	public function initialize(array $config) : void
		{
		parent::initialize($config);
		$this->setTable('string_fixed_records');
		$this->setPrimaryKey('string_fixed_id');
		$this->setEntityClass('ThePHPBench\\CakeCached\\Record\\StringFixed');
		}
	}
