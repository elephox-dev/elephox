<?php
declare(strict_types=1);

namespace App\Models;

use Elephox\Database\AbstractEntity;
use Elephox\Database\Attributes\Generated;

class User extends AbstractEntity
{
	#[Generated] private int $id;
	private string $username;
	private string $password;
	private string $email;

	public static function from(string $username, string $password, string $email): User
	{
		$user = new self;

		$user->username = $username;
		$user->setPassword($password);
		$user->email = $email;

		return $user;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setPassword(string $password): void
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);
	}

	public function verifyPassword(string $password): bool
	{
		if (!password_verify($password, $this->password)) {
			return false;
		}

		if (password_needs_rehash($this->password, PASSWORD_DEFAULT)) {
			$this->setPassword($password);
		}

		return true;
	}
}
