<?php

namespace ThePHPBench\Cake\Table;

class Simple extends \Cake\ORM\Table
	{
	public function initialize(array $config) : void
		{
		parent::initialize($config);
		$this->setTable('simple_records');
		$this->setPrimaryKey('simple_id');
		$this->setEntityClass('ThePHPBench\\Cake\\Record\\Simple');
		}
	}
