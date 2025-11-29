<?php

use AndreMoura\CardBank\Router;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @var Router $router
 */
$router = require_once __DIR__ . '/../src/bootstrap.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * @var Response $response
 */
$response = $router->dispatch($_SERVER['PATH_INFO'] ?? '/');

$response->send();
