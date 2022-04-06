<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/vendor/autoload.php';

use App\Middlewares\FileExtensionToContentType;
use App\Middlewares\ProductionExceptionHandler;
use Elephox\Support\Contract\ExceptionHandler;
use Elephox\Web\Routing\RequestRouter;
use Elephox\Web\WebApplicationBuilder;

$builder = WebApplicationBuilder::create();
if ($builder->environment->isDevelopment()) {
	$builder->addWhoops();
} else {
	$handler = new ProductionExceptionHandler();
	$builder->services->addSingleton(ExceptionHandler::class, implementation: $handler);
	$builder->pipeline->exceptionHandler($handler);
}

$builder->pipeline->push(new FileExtensionToContentType());
$builder->addDoctrine();
$builder->setRequestRouterEndpoint();
$builder->service(RequestRouter::class)->loadFromNamespace('App\\Routes');

$app = $builder->build();
$app->run();
