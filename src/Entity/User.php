<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity, UniqueConstraint(columns: ["email"])]
class User
{
	public function __construct(
		#[Id, Column(type: 'guid')]
		public string $uuid,
		#[Column(type: 'string')]
		public string $email,
		#[Column(type: 'binary')]
		public string $password
	) {
	}
}
