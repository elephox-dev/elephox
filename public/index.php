<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$serialized = file_get_contents('cache/web.phpo');
/** @var \Elephox\Web\WebApplication $app */
$app = unserialize($serialized);

$app->run();
