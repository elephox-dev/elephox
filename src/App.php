<?php
declare(strict_types=1);

namespace App;

use Elephox\Core\Handler\ActionType;
use Elephox\Core\Handler\Contract\RequestContext;
use Elephox\Core\Handler\HandlerAttribute;
use Elephox\Http\Contract;
use Elephox\Http\HeaderName;
use Elephox\Http\Response;
use Elephox\Http\ResponseHeaderMap;

class App
{
    #[HandlerAttribute(ActionType::Request)]
    public function handleIndex(RequestContext $context): Contract\Response
    {
        return Response::withJson(
            [
                'message' => 'Hello, World!',
                'url' => $context->getRequest()->getUrl()->asString(),
                'ts' => microtime(true) - ELEPHOX_START
            ],
        );
    }
}
