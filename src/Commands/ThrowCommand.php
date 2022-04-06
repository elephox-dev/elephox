<?php
declare(strict_types=1);

namespace App\Commands;

use Elephox\Console\Command\CommandInvocation;
use Elephox\Console\Command\CommandTemplateBuilder;
use Elephox\Console\Command\Contract\CommandHandler;
use Elephox\Logging\Contract\Logger;
use RuntimeException;

class ThrowCommand implements CommandHandler
{
	public function __construct(
		private readonly Logger $logger,
	)
	{
	}

	public function configure(CommandTemplateBuilder $builder): void
	{
		$builder
			->name('throw')
			->description('Throws an exception')
			->argument('message', 'The message to throw')
		;
	}

	public function handle(CommandInvocation $command): int|null
	{
		$this->logger->warning("Throwing an exception in...");
		$this->logger->warning("3...");
		sleep(1);
		$this->logger->warning("2...");
		sleep(1);
		$this->logger->warning("1...");
		sleep(1);

		throw new RuntimeException($command->getArgument('message')->value);
	}
}
