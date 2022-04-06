<?php
declare(strict_types=1);

namespace App\Routes;

use Elephox\Http\Contract\ResponseBuilder;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
use Elephox\Mimey\MimeType;
use Elephox\Web\Routing\Attribute\Controller;
use Elephox\Web\Routing\Attribute\Http\Get;
use RuntimeException;

#[Controller("/")]
class WebController
{
	#[Get]
	public function index(): ResponseBuilder
	{
		return Response::build()
			->responseCode(ResponseCode::OK)
			->fileBody(APP_ROOT . '/views/index.html', MimeType::TextHtml)
		;
	}

	#[Get]
	public function throw(): ResponseBuilder
	{
		throw new RuntimeException('Test exception');
	}
}
