<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Elephox\Database\AbstractRepository;
use Elephox\Database\Contract\Storage;
use Elephox\DI\Contract\Container;

/**
 * @extends AbstractRepository<User>
 */
class UserRepository extends AbstractRepository
{
	public function __construct(Storage $storage, Container $container)
	{
		parent::__construct(User::class, $storage, $container);
	}
}
