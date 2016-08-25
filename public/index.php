<?php

$config = [];
foreach (glob(__DIR__ . "/../config/*.php") as $configFile) {
    $partConfig = require_once($configFile);
    $config = array_merge_recursive($config, $partConfig);
}

require __DIR__ . '/../vendor/autoload.php';

$app = new Slim\App($config);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';
// Register routes
require __DIR__ . '/../app/routes.php';
// Run!
$app->run();


