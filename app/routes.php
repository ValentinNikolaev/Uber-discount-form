<?php
// Routes
$app->get('/', App\Action\HomeAction::class);

$app->get('/estimate', App\Action\EstimateAction::class);
