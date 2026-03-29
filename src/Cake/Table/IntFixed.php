<?php

namespace ThePHPBench\Cake\Table;

class IntFixed extends \Cake\ORM\Table
	{
	public function initialize(array $config) : void
		{
		parent::initialize($config);
		$this->setAlias('IntFixedRecords');
		$this->setTable('int_fixed_records');
		$this->setPrimaryKey('int_fixed_id');
		$this->setEntityClass('ThePHPBench\\Cake\\Record\\IntFixed');
		}
	}
