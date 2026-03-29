<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV2\Model;

use Divergence\Models\ActiveRecord;
use Divergence\Models\Getters;
use Divergence\Models\Mapping\Column;

class StringVariable extends ActiveRecord
	{
	use Getters;

	public static $tableName = 'string_variable_records';
	public static $primaryKey = 'string_variable_id';
	public static $singularNoun = 'string variable record';
	public static $pluralNoun = 'string variable records';

	public static $indexes = [
		'email' => ['fields' => ['email']],
		'company' => ['fields' => ['company']],
		'postal_code' => ['fields' => ['postal_code']],
	];

	#[Column(type: 'integer', primary: true, autoincrement: true, unsigned: true)]
	protected $string_variable_id;

	#[Column(type: 'string', length: 160, notnull: false)] protected $company;
	#[Column(type: 'string', length: 64)] protected $name;
	#[Column(type: 'string', length: 128)] protected $title;
	#[Column(type: 'string', length: 160)] protected $email;
	#[Column(type: 'string', length: 64)] protected $city;
	#[Column(type: 'string', length: 64)] protected $region;
	#[Column(type: 'string', length: 32)] protected $postal_code;
	#[Column(type: 'string', length: 64)] protected $country;
	#[Column(type: 'string', length: 32)] protected $phone;
	#[Column(type: 'clob')] protected $notes;
	}
