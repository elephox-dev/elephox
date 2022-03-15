<?php
declare(strict_types=1);

$app = include '../buildApp.php';

// runtime configuration
if ($app->environment->isDevelopment()) {
	$app->useDeveloperExceptionPages();
} else {
	$app->useHsts();
}

$app->handleGlobal(); // handle global context
