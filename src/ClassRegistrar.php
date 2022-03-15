<?php
declare(strict_types=1);

namespace App;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Contract\App as AppContract;
use Elephox\Core\Contract\AppBuilder;
use Elephox\Core\Contract\Registrar as RegistrarContract;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Handler\Attribute\Http\Get;
use Elephox\Core\Registrar;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
use Elephox\Logging\ConsoleSink;
use Elephox\Logging\Contract\Sink;
use Elephox\Logging\GenericSinkLogger;
use Elephox\Stream\StringStream;
use RuntimeException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRunner;

class ClassRegistrar implements RegistrarContract
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
}
