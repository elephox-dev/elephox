<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\ClassRegistrar;
use Elephox\Core\Core;
use Elephox\Core\SimpleWebApp;

$core = Core::create();
$core->registerApp(ClassRegistrar::class);
$core->registerGlobal();
$builder = SimpleWebApp::createBuilder();

if (!$builder->hasChanged()) {
	return $builder->getCached();
}

return $builder->build();
