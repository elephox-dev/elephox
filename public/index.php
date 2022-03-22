<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use Elephox\Web\Middleware\DefaultExceptionHandler;
use Elephox\Web\WebApplication;

$builder = WebApplication::createBuilder();
$builder->setRequestRouterEndpoint();
$builder->addWhoops();
$builder->addDoctrine();
$builder->build()->run();
