<?php

namespace ThePHPBench\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'canaries')]
class Canary
	{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	public int $canary_id;

	#[ORM\Column(type: 'boolean')]
	public bool $bool_flag;

	#[ORM\Column(type: 'smallint')]
	public int $small_int;

	#[ORM\Column(type: 'integer')]
	public int $int_value;

	#[ORM\Column(type: 'bigint')]
	public int $big_int;

	#[ORM\Column(type: 'float')]
	public float $float_value;

	#[ORM\Column(type: 'float')]
	public float $double_value;

	#[ORM\Column(type: 'decimal', precision: 12, scale: 4)]
	public string $decimal_value;

	#[ORM\Column(type: 'string', length: 8)]
	public string $fixed_char_8;

	#[ORM\Column(type: 'string', length: 16)]
	public string $fixed_char_16;

	#[ORM\Column(type: 'string', length: 32)]
	public string $string_short;

	#[ORM\Column(type: 'string', length: 128)]
	public string $string_medium;

	#[ORM\Column(type: 'string', length: 512)]
	public string $string_long;

	#[ORM\Column(type: 'text')]
	public string $text_value;

	#[ORM\Column(type: 'date_immutable')]
	public \DateTimeImmutable $date_value;

	#[ORM\Column(type: 'datetime_immutable')]
	public \DateTimeImmutable $datetime_value;

	#[ORM\Column(type: 'string', length: 64, nullable: true)]
	public ?string $nullable_string = null;

	#[ORM\Column(type: 'integer', nullable: true)]
	public ?int $nullable_int = null;

	}
