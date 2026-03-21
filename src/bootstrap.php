<?php

use CardCollection\Controllers\StatusController;
use CardCollection\Router;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$router->add('home', '/', function (): Response {
    return new Response('Welcome to the Card Collection Application! 😊');
});

$router->add('status', '/api/v1/status', [StatusController::class, 'showStatus']);

$router->add('phpinfo', '/phpinfo', function (): Response {
    ob_start();
    phpinfo();
    $phpInfo = ob_get_clean();
    return new Response($phpInfo);
});

return $router;
