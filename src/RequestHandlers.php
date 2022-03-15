<?php

namespace App;

use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Handler\Attribute\Http\Get;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
use Elephox\Stream\StringStream;
use RuntimeException;

class RequestHandlers
{
	#[Get]
	public function handleIndex(): Response
	{
		return Response::build()
			->responseCode(ResponseCode::OK)
			->body(new StringStream('Hello world!'))
			->get();
	}

	#[Get('throw')]
	public function testThrow(): never
	{
		throw new RuntimeException('Test exception');
	}

	#[Any('(?<anything>.*)')]
	public function catchAll(string $anything): Response
	{
		return Response::build()
			->responseCode(ResponseCode::NotFound)
			->body(new StringStream("Requested resource not found: $anything"))
			->get();
	}
}
