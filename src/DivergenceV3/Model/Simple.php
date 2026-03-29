<?php

declare(strict_types=1);

namespace ThePHPBench\DivergenceV3\Model;

use Divergence\Models\ActiveRecord;
use Divergence\Models\Getters;
use Divergence\Models\Mapping\Column;

class Simple extends ActiveRecord
	{
	use Getters;

	public static $tableName = 'simple_records';
	public static $primaryKey = 'simple_id';
	public static $singularNoun = 'simple record';
	public static $pluralNoun = 'simple records';

	public static $indexes = [
		'title' => ['fields' => ['title']],
	];

	#[Column(type: 'integer', primary: true, autoincrement: true, unsigned: true)]
	protected ?int $simple_id = null;

	#[Column(type: 'string', length: 128)]
	protected string $title;
	}
