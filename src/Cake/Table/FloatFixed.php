<?php

namespace ThePHPBench\Cake\Table;

class FloatFixed extends \Cake\ORM\Table
	{
	public function initialize(array $config) : void
		{
		parent::initialize($config);
		$this->setAlias('FloatFixedRecords');
		$this->setTable('float_fixed_records');
		$this->setPrimaryKey('float_fixed_id');
		$this->setEntityClass('ThePHPBench\\Cake\\Record\\FloatFixed');
		}
	}
