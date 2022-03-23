<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Middlewares\FileExtensionToContentType;
use App\Middlewares\ProductionExceptionHandler;
use Elephox\Web\Endpoint\RequestRouter;
use Elephox\Web\WebApplication;

$builder = WebApplication::createBuilder();
$builder->setRequestRouterEndpoint();
$router = $builder->services->requireService(RequestRouter::class);
$router->loadFromNamespace('App\\Routes');
if ($builder->environment->isDevelopment()) {
	$builder->addWhoops();
} else {
	$builder->pipeline->push(new ProductionExceptionHandler());
}
$builder->pipeline->push(new FileExtensionToContentType());
$builder->addDoctrine();
$builder->build()->run();
