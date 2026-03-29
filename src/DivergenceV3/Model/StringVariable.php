<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV3\Model;

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
	protected ?int $string_variable_id = null;

	#[Column(type: 'string', length: 160)]
	protected ?string $company = null;

	#[Column(type: 'string', length: 64)]
	protected string $name;

	#[Column(type: 'string', length: 128)]
	protected string $title;

	#[Column(type: 'string', length: 160)]
	protected string $email;

	#[Column(type: 'string', length: 64)]
	protected string $city;

	#[Column(type: 'string', length: 64)]
	protected string $region;

	#[Column(type: 'string', length: 32)]
	protected string $postal_code;

	#[Column(type: 'string', length: 64)]
	protected string $country;

	#[Column(type: 'string', length: 32)]
	protected string $phone;

	#[Column(type: 'clob')]
	protected string $notes;
	}
