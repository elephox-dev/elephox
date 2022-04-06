<?php
declare(strict_types=1);

namespace App\Middlewares;

use Elephox\Http\Contract\ResponseBuilder;
use Elephox\Mimey\MimeType;
use Elephox\Web\Middleware\DefaultExceptionHandler;

class ProductionExceptionHandler extends DefaultExceptionHandler
{
	protected function setResponseBody(ResponseBuilder $response): ResponseBuilder
	{
		return $response->fileBody(APP_ROOT . '/views/error500.html', MimeType::TextHtml);
	}
}
