<?php
declare(strict_types=1);

namespace App\Commands;

use Elephox\Console\Command\CommandInvocation;
use Elephox\Console\Command\CommandTemplateBuilder;
use Elephox\Console\Command\Contract\CommandHandler;
use Psr\Log\LoggerInterface;

class EchoCommand implements CommandHandler
{
	public function __construct(
		private readonly LoggerInterface $logger,
	)
	{
	}

	public function configure(CommandTemplateBuilder $builder): void
	{
		$builder
			->name('echo')
			->argument('message', 'A string to echo back')
		;
	}

	public function handle(CommandInvocation $command): int|null
	{
		$echo = $command->message;

		$this->logger->info("You typed: $echo");

		return 0;
	}
}
