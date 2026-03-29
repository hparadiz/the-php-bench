<?php

namespace ThePHPBench\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'float_fixed_records')]
class FloatFixed
	{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	public int $float_fixed_id;

	#[ORM\Column(type: 'float')]
	public float $f01;

	#[ORM\Column(type: 'float')]
	public float $f02;

	#[ORM\Column(type: 'float')]
	public float $d01;

	#[ORM\Column(type: 'float')]
	public float $d02;

	#[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
	public string $n01;

	#[ORM\Column(type: 'decimal', precision: 12, scale: 4)]
	public string $n02;

	#[ORM\Column(type: 'decimal', precision: 18, scale: 6)]
	public string $n03;

	#[ORM\Column(type: 'decimal', precision: 20, scale: 8)]
	public string $n04;

	}
