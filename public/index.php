<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use Elephox\Configuration\Json\JsonFileConfigurationSource;
use Elephox\Host\WebApplication;

$builder = WebApplication::createBuilder();
$builder->configuration->add(new JsonFileConfigurationSource('config.json'));
$builder->configuration->add(new JsonFileConfigurationSource('config.' . $builder->environment->getEnvironmentName() . '.json', true));
$builder->configuration->add(new JsonFileConfigurationSource('config.local.json', true));
$app = $builder->build();

if ($app->environment->isDevelopment()) {
	$app->services->addWhoops();
}

$app->services->addDoctrine();

$app->run();
