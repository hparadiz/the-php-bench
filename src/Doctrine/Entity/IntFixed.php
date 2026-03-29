<?php

namespace ThePHPBench\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'int_fixed_records')]
class IntFixed
	{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	public int $int_fixed_id;

	#[ORM\Column(type: 'integer')]
	public int $i01;

	#[ORM\Column(type: 'integer')]
	public int $i02;

	#[ORM\Column(type: 'integer')]
	public int $i03;

	#[ORM\Column(type: 'integer')]
	public int $i04;

	#[ORM\Column(type: 'integer')]
	public int $i05;

	#[ORM\Column(type: 'integer')]
	public int $i06;

	#[ORM\Column(type: 'integer')]
	public int $i07;

	#[ORM\Column(type: 'integer')]
	public int $i08;

	#[ORM\Column(type: 'integer')]
	public int $i09;

	#[ORM\Column(type: 'integer')]
	public int $i10;

	#[ORM\Column(type: 'integer')]
	public int $i11;

	#[ORM\Column(type: 'integer')]
	public int $i12;

	}
