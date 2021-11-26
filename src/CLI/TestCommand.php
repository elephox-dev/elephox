<?php
declare(strict_types=1);

namespace App\CLI;

use Elephox\Core\Handler\Attribute\CommandHandler;

#[CommandHandler('/test\s(?<name>.*)/i')]
class TestCommand
{
	public function __invoke(string $name)
	{
		echo "Hello, {$name}!";
	}
}
