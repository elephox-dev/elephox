<?php
declare(strict_types=1);

namespace App\Commands;

use Elephox\Console\Command\CommandInvocation;
use Elephox\Console\Command\CommandTemplateBuilder;
use Elephox\Console\Command\Contract\CommandHandler;
use Psr\Log\LoggerInterface;
use RuntimeException;

class ThrowCommand implements CommandHandler
{
	public function __construct(
		private readonly LoggerInterface $logger,
	)
	{
	}

	public function configure(CommandTemplateBuilder $builder): void
	{
		$builder
			->setName('throw')
			->setDescription('Throws an exception')
			->addArgument('message', description: 'The message to throw')
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

		throw new RuntimeException($command->arguments->message->value);
	}
}
