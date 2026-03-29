<?php

namespace ThePHPBench\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'string_variable_records')]
class StringVariable
	{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	public int $string_variable_id;

	#[ORM\Column(type: 'string', length: 64)]
	public string $name;

	#[ORM\Column(type: 'string', length: 128)]
	public string $title;

	#[ORM\Column(type: 'string', length: 160)]
	public string $email;

	#[ORM\Column(type: 'string', length: 160)]
	public string $company;

	#[ORM\Column(type: 'string', length: 64)]
	public string $city;

	#[ORM\Column(type: 'string', length: 64)]
	public string $region;

	#[ORM\Column(type: 'string', length: 32)]
	public string $postal_code;

	#[ORM\Column(type: 'string', length: 64)]
	public string $country;

	#[ORM\Column(type: 'string', length: 32)]
	public string $phone;

	#[ORM\Column(type: 'text')]
	public string $notes;

	}
