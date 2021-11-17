<?php
declare(strict_types=1);

namespace App;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Context\Contract\RequestContext;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Core\Handler\Attribute\RequestHandler;
use Elephox\Http\Contract;
use Elephox\Http\RequestMethod;
use Elephox\Http\Response;

class App
{
    #[RequestHandler('/')]
    public function handleIndex(): Contract\Response
    {
        return Response::withJson(
            [
                'message' => 'Hello, World! Send a POST request to /login to log in.',
                'ts' => microtime(true) - ELEPHOX_START
            ],
        );
    }

    #[RequestHandler('login', RequestMethod::POST)]
    public function handleLogin(RequestContext $context): Contract\Response
    {
        return Response::withJson([]);
    }

    #[RequestHandler('{anything}')]
    public function catchAll(RequestContext $context): Contract\Response
    {
        return Response::withJson([
            'message' => 'You sent a request to an invalid endpoint: ' . $context->getRequest()->getUrl(),
        ]);
    }

    #[CommandHandler]
    public function commandLineHandler(CommandLineContext $context): void
    {
        echo "You successfully invoked your Elephox app from command line!";
    }

    #[ExceptionHandler]
    public function globalExceptionHandler(ExceptionContext $context): void
    {
        echo <<<TEXT
Uh-oh! This shouldn't have happened. An exception ocurred at {$context->getException()->getFile()}:{$context->getException()->getLine()}

> {$context->getException()->getMessage()}

{$context->getException()->getTraceAsString()}
TEXT;
    }
}
