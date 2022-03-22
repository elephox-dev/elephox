<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use Elephox\Host\Middleware\DefaultExceptionHandler;
use Elephox\Host\WebApplication;

$builder = WebApplication::createBuilder();

$builder->services->addWhoops();

$builder->pipeline->addRouting();
$builder->pipeline->push(new DefaultExceptionHandler());

$builder->build()->run();
