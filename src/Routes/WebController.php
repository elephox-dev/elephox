<?php
declare(strict_types=1);

namespace App\Routes;

use Elephox\Http\Contract\ResponseBuilder;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
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
			->htmlBody('<h1>Hello world!</h1>');
	}

	#[Get]
	public function throw(): ResponseBuilder
	{
		throw new RuntimeException('Test exception');
	}
}
