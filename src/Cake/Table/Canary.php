<?php

namespace ThePHPBench\Cake\Table;

class Canary extends \Cake\ORM\Table
	{
	public function initialize(array $config) : void
		{
		parent::initialize($config);
		$this->setTable('canaries');
		$this->setPrimaryKey('canary_id');
		$this->setEntityClass('ThePHPBench\\Cake\\Record\\Canary');
		}
	}
