<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\App;
use Elephox\Core\Core;

define("ELEPHOX_START", microtime(true));

$core = Core::create();

$core->registerApp(App::class);
$core->registerGlobal();

return $core;
