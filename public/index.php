<?php
declare(strict_types=1);

use App\App;
use Dotenv\Dotenv;
use Elephox\Core\Core;

//register_shutdown_function(static function () {
//	$time = microtime(true) - ELEPHOX_START;
//	echo "\nExecution time: {$time}s\n";
//});

define("ELEPHOX_START", microtime(true));

require_once dirname(__DIR__) . "/vendor/autoload.php";

Dotenv::createImmutable(dirname(__DIR__), ['.env.local', '.env'])->load();

Core::entrypoint();
Core::setApp(App::class);
Core::loadHandlersInNamespace('App\\CLI');
Core::handle();
