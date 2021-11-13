<?php
declare(strict_types=1);

define("ELEPHOX_START", microtime(true));

require_once dirname(__DIR__) . "/vendor/autoload.php";

Elephox\Core\Core::entrypoint();
