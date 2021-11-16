<?php
declare(strict_types=1);

namespace App;

use Elephox\Core\Handler\Contract\RequestContext;
use Elephox\Core\Handler\RequestHandler;
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

    #[RequestHandler('/login', RequestMethod::POST)]
    public function handleLogin(RequestContext $context): Contract\Response
    {

    }
}
