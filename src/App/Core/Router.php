<?php

namespace Sk\App\Core;

use Sk\App\Core\Middleware;
use Sk\App\Core\ShowView;

class Router {
    protected $routes = [];
    protected $middleware;

    public function __construct()
    {
        $this->middleware = new Middleware();
    }

    public function addRoute($method, $path, $handler, $middleware = null)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function handleRequest($requestUri)
    {
        foreach ($this->routes as $route) {
            if ($route['path'] === $requestUri && $_SERVER['REQUEST_METHOD'] === $route['method']) {
                if ($route['middleware']) {
                    $this->middleware->handle($route['middleware'], function() use ($route) {
                        $this->executeHandler($route['handler']);
                    });
                } else {
                    error_log(json_encode($route['handler']));
                    $this->executeHandler($route['handler']);
                    error_log("Route".$route['handler']);
                }
                return;
            }
        }
        $this->notFound();
    }

    protected function executeHandler($handler){
        if (is_callable($handler)) {
            call_user_func($handler);
        } else {
            //list($controllerName, $methodName) = explode('@', $handler);
            list($controllerName, $methodName) = $handler;
            $controller = new $controllerName();
            $controller->$methodName();
        }
    }

    protected function notFound(){
        http_response_code(404);
        ShowView::render('');
    }
}
