<?php

namespace ThePHPBench\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'simple_records')]
class Simple
	{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	public int $simple_id;

	#[ORM\Column(type: 'string', length: 128)]
	public string $title;

	}
