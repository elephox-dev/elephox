<?php
declare(strict_types=1);

use App\App;
use Dotenv\Dotenv;
use Elephox\Core\Core;

define("ELEPHOX_START", microtime(true));

require_once dirname(__DIR__) . "/vendor/autoload.php";

Dotenv::createImmutable(dirname(__DIR__), ['.env.local', '.env'])->load();

Core::entrypoint();
Core::setApp(App::class);
Core::handle();
