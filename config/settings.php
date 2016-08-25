<?php

return [
    'settings' => [
        'after_prefix' => 'After',
        'discount' => 0.2,
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        // View settings
        'view' => [
            'template_path' => __DIR__ . '/../app/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
                'charset' => 'utf-8',
                'strict_variables' => false,
                'autoescape' => true
            ],
        ],
        // monolog settings
        'logger' => [
            'name' => 'AfterUberApp',
            'path' => __DIR__ . '/../log/app.log',
        ],
        'google' => [
            'api_key' => 'AIzaSyCKwSlnPqXQZvXL5LKaQrZKI0FxJl7D9l8']
    ],
];