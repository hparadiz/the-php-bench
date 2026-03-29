<?php

namespace ThePHPBench\CakeCached\Table;

class StringVariable extends \Cake\ORM\Table
	{
	public function initialize(array $config) : void
		{
		parent::initialize($config);
		$this->setTable('string_variable_records');
		$this->setPrimaryKey('string_variable_id');
		$this->setEntityClass('ThePHPBench\\CakeCached\\Record\\StringVariable');
		}
	}
