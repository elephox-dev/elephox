<?php
declare(strict_types=1);

use App\App;
use Elephox\Core\Core;

define("ELEPHOX_START", microtime(true));

require_once dirname(__DIR__) . "/vendor/autoload.php";

$core = Core::create();
$core->registerApp(App::class);
$core->handleGlobal();
