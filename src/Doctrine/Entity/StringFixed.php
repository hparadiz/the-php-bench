<?php

namespace ThePHPBench\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'string_fixed_records')]
class StringFixed
	{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	public int $string_fixed_id;

	#[ORM\Column(type: 'string', length: 8)]
	public string $c08_01;

	#[ORM\Column(type: 'string', length: 8)]
	public string $c08_02;

	#[ORM\Column(type: 'string', length: 16)]
	public string $c16_01;

	#[ORM\Column(type: 'string', length: 16)]
	public string $c16_02;

	#[ORM\Column(type: 'string', length: 32)]
	public string $c32_01;

	#[ORM\Column(type: 'string', length: 32)]
	public string $c32_02;

	#[ORM\Column(type: 'string', length: 64)]
	public string $c64_01;

	#[ORM\Column(type: 'string', length: 64)]
	public string $c64_02;

	}
