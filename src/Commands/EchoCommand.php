<?php
declare(strict_types=1);

namespace App\Commands;

use Elephox\Console\Command\CommandInvocation;
use Elephox\Console\Command\CommandTemplateBuilder;
use Elephox\Console\Command\Contract\CommandHandler;
use Elephox\Logging\Contract\Logger;

class EchoCommand implements CommandHandler
{
	public function __construct(
		private readonly Logger $logger,
	)
	{
	}

	public function configure(CommandTemplateBuilder $builder): CommandTemplateBuilder
	{
		return $builder
			->name('echo')
			->argument('message', 'A string to echo back', required: true)
		;
	}

	public function handle(CommandInvocation $command): int|null
	{
		$echo = $command->message;

		$this->logger->info("You typed: $echo");

		return 0;
	}
}
