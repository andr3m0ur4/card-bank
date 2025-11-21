<?php

namespace CardCollection;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    private RouteCollection $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function add(string $name, string $path, mixed $controller = null): void
    {
        $defaults = ['_controller' => $controller];
        $route = new Route($path, $defaults);
        $this->routes->add($name, $route);
    }

    public function dispatch(string $path): Response
    {
        $context = new RequestContext();

        $matcher = new UrlMatcher($this->routes, $context);

        $parameters = $matcher->match($path);
        $controller = $parameters['_controller'];

        if (is_callable($controller)) {
            return call_user_func($controller);
        }

        if (is_array($controller) && count($controller) === 2) {
            [$class, $method] = $controller;
            $instance = new $class();
            return call_user_func([$instance, $method]);
        }

        throw new RuntimeException('Controller not found or invalid.');
    }
}
