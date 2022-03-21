<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use Elephox\Configuration\Json\JsonFileConfigurationSource;
use Elephox\Host\WebApplication;

$builder = WebApplication::createBuilder();
$app = $builder->build();

if ($app->environment->isDevelopment()) {
	$app->services->addWhoops();
}

$app->run();
