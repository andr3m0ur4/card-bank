<?php

use AndreMoura\CardBank\Router;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @var Router $router
 */
$router = require_once __DIR__ . '/../src/bootstrap.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

/**
 * @var Response $response
 */
$response = $router->dispatch($_SERVER['PATH_INFO'] ?? '/');

$response->send();
