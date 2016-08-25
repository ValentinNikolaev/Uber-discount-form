<?php
// Routes
$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->get('/estimates', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name']);
    return $response;
});

$app->get('/place', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name']);
    return $response;
});
