<?php
declare(strict_types=1);

namespace App;

use Attribute;
use Closure;
use Elephox\Core\Context\Contract\Context;
use Elephox\Core\Middleware\Attribute\RequestMiddleware;
use Elephox\Http\Contract\Response;
use Elephox\Stream\AppendStream;
use Elephox\Stream\StringStream;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ResponseStreamModifier extends RequestMiddleware
{
	public function handle(Context $context, Closure $next): mixed
	{
		$result = $next($context);
		if (!$result instanceof Response) {
			return $result;
		}

		return $result->withBody(
			new AppendStream(
				$result->getBody(),
				new StringStream('<!-- ResponseStreamModifier was here -->')
			)
		);
	}
}
