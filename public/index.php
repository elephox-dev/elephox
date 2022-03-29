<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/vendor/autoload.php';

use App\Middlewares\FileExtensionToContentType;
use App\Middlewares\ProductionExceptionHandler;
use Elephox\Web\Routing\RequestRouter;
use Elephox\Web\WebApplication;

$builder = WebApplication::createBuilder();
if ($builder->environment->isDevelopment()) {
	$builder->addWhoops();
} else {
	$builder->pipeline->push(new ProductionExceptionHandler());
}
$builder->pipeline->push(new FileExtensionToContentType());
$builder->addDoctrine();

$builder->setRequestRouterEndpoint();
$router = $builder->services->requireService(RequestRouter::class);
$router->loadFromNamespace('App\\Routes');

$app = $builder->build();
$app->run();
