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
                'message' => 'Hello, World! Send a POST request to /info to get more info.',
                'ts' => microtime(true) - ELEPHOX_START,
            ],
        );
    }

    #[RequestHandler('info', RequestMethod::POST)]
    public function info(RequestContext $context): Contract\Response
    {
        return Response::withJson([
            'message' => "You successfully POSTed! Next, try to comment out the 'catchAll' handler and see your exception handler at work.",
            'ts' => microtime(true) - ELEPHOX_START,
        ]);
    }

    #[RequestHandler('{anything}')]
    public function catchAll(RequestContext $context): Contract\Response
    {
        return Response::withJson([
            'message' => "You sent a request to an invalid endpoint: {$context->getRequest()->getUrl()}. Perhaps your request method ({$context->getRequest()->getMethod()->getValue()}) was invalid?",
            'ts' => microtime(true) - ELEPHOX_START,
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
        headers_sent() || header('Content-Type: text/html');

        echo <<<TEXT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Elephox Exception</title>
</head>
<body>
    <h1>Oops!</h1>
    <p>
        An error occurred while processing your request.
    </p>
    <p>
        <strong>Error:</strong> {$context->getException()->getMessage()}
    </p>
    <p>
        <strong>File:</strong> {$context->getException()->getFile()}:{$context->getException()->getLine()}
    </p>
    <p>
        <strong>Trace:</strong>
    </p>
    <pre>
{$context->getException()->getTraceAsString()}
    </pre>
</body>
</html>
TEXT;
    }
}
