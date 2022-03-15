<?php

namespace App;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Logging\GenericSinkLogger;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRunner;

#[ExceptionHandler]
class GlobalExceptionHandler
{
	public function __invoke(ExceptionContext $context, ?CommandLineContext $commandLineContext, GenericSinkLogger $logger, WhoopsRunner $whoops): void
	{
		// handle command line exceptions
		if ($commandLineContext !== null) {
			$logger->error($context->getException()->getMessage());

			exit(1);
		}

		// fallback handler for all other exceptions
		$whoops->pushHandler(new PrettyPageHandler);
		$whoops->handleException($context->getException());
	}
}
