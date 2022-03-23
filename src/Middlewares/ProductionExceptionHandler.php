<?php
declare(strict_types=1);

namespace App\Middlewares;

use Closure;
use Elephox\Http\Contract\Request;
use Elephox\Http\Contract\ResponseBuilder;
use Elephox\Http\Response;
use Elephox\Web\Contract\WebMiddleware;

class ProductionExceptionHandler implements WebMiddleware
{
	public function handle(Request $request, Closure $next): ResponseBuilder
	{
		$response = $next($request);

		if ($exception = $response->getException()) {
			return Response::build()
				->exception($exception)
				->htmlBody(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
	<title>Internal Server Error</title>
</head>
<body>
	<section class="section">
		<div class="container">
			<p class="title is-1">Internal Server Error</p>

			<div class="box">
				<p class="title is-4">An unexpected error occurred</p>

				<p>
					That's all we know, unfortunately.
				</p>
			</div>
		</div>
	</section>
</body>
</html>
HTML)
			;
		}

		return $response;
	}
}
