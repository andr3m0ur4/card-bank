<?php

use CardCollection\Controllers\StatusController;
use CardCollection\Router;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$router->add('home', '/', function (): Response {
    return new Response('Welcome to the Card Collection Application! 😊');
});

$router->add('status', '/api/v1/status', [StatusController::class, 'showStatus']);

return $router;
