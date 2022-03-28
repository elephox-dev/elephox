<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

if (!file_exists(dirname(__DIR__) . '/cache/web.phpo')) {
	/** @var \Elephox\Web\WebApplication $app */
	$app = require dirname(__DIR__) . '/buildApp.php';
	$serialized = serialize($app);

	file_put_contents(dirname(__DIR__) . '/cache/web.phpo', $serialized);
} else {
	$serialized = file_get_contents(dirname(__DIR__) . '/cache/web.phpo');

	/** @var \Elephox\Web\WebApplication $app */
	$app = unserialize($serialized);
}

$app->run();
