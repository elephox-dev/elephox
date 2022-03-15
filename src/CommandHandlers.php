<?php

namespace App;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Logging\GenericSinkLogger;

class CommandHandlers
{
	#[CommandHandler]
	public function commandLineHandler(CommandLineContext $context, GenericSinkLogger $logger): int
	{
		$logger->info("You successfully invoked your Elephox app from command line!");
		$logger->info("\tCommand: " . $context->getCommand());

		return 0;
	}
}
