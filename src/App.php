<?php
declare(strict_types=1);

namespace App;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Context\Contract\RequestContext;
use Elephox\Core\Contract\App as AppContract;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Handler\Attribute\Http\Get;
use Elephox\Core\Registrar;
use Elephox\Http\Contract;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
use Elephox\Stream\StringStream;
use Elephox\Logging\ConsoleSink;
use Elephox\Logging\Contract\Sink;
use Elephox\Logging\GenericSinkLogger;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRunner;

class App implements AppContract
{
	use Registrar;

	public array $classes = [
		WhoopsRunner::class,
		ConsoleSink::class,
		GenericSinkLogger::class
	];

	public array $aliases = [
		Sink::class => ConsoleSink::class,
	];

	#[Get('/')]
	#[ResponseStreamModifier]
	#[ResponseStreamModifier]
	#[ResponseStreamModifier]
	public function handleIndex(): Contract\Response
	{
		return new Response(body: new StringStream("Hello world!"));
	}

	#[Any('(?<anything>.*)')]
	public function catchAll(string $anything): Contract\Response
	{
		return new Response(ResponseCode::NotFound, body: new StringStream("Requested resource not found: $anything"));
	}

	#[CommandHandler]
	public function commandLineHandler(CommandLineContext $context, GenericSinkLogger $logger): void
	{
		$logger->info("You successfully invoked your Elephox app from command line!");
		$logger->info("\tCommand: " . $context->getCommand());
	}

	#[ExceptionHandler]
	public function globalExceptionHandler(ExceptionContext $context, ?CommandLineContext $commandLineContext, GenericSinkLogger $logger, WhoopsRunner $whoops): void
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
