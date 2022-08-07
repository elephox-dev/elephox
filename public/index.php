<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/vendor/autoload.php';

use App\Middlewares\FileExtensionToContentType;
use App\Middlewares\ProductionExceptionHandler;
use App\Middlewares\StaticContentHandler;
use Elephox\Builder\Doctrine\AddsDoctrine;
use Elephox\Builder\Whoops\AddsWhoopsMiddleware;
use Elephox\Support\Contract\ExceptionHandler;
use Elephox\Web\Routing\RequestRouter;
use Elephox\Web\WebApplicationBuilder;

class Builder extends WebApplicationBuilder {
	use AddsWhoopsMiddleware;
	use AddsDoctrine;
}

$builder = Builder::create();
if ($builder->environment->isDevelopment()) {
	$builder->addWhoops();
} else {
	$handler = new ProductionExceptionHandler();
	$builder->services->addSingleton(ExceptionHandler::class, implementation: $handler);
	$builder->pipeline->exceptionHandler($handler);
}

$builder->pipeline
	->push(FileExtensionToContentType::class)
	->push(StaticContentHandler::class);
$builder->addDoctrine();
$builder->setRequestRouterEndpoint();
$builder->service(RequestRouter::class)->loadFromNamespace('App\\Routes');

$app = $builder->build();
$app->run();
