<?php

namespace App\Routes;

use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Handler\Attribute\Http\Get;
use Elephox\Http\Contract\Response as ResponseContract;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
use Elephox\Stream\StringStream;
use RuntimeException;

#[Controller("/")]
class RequestHandlers
{
	#[Get]
	public function index(): ResponseContract
	{
		return Response::build()
			->responseCode(ResponseCode::OK)
			->htmlBody('<h1>Hello world!</h1>')
			->get();
	}

	#[Get]
	public function throw(): never
	{
		throw new RuntimeException('Test exception');
	}
}
