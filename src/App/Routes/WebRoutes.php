<?php
namespace Sk\App\Routes;

use Sk\App\Core\Router;
use Sk\App\Controllers\HomeController;
use Sk\App\Controllers\AuthController;

// Add controllers

class WebRoutes {
    public function __construct(){}

    public static function getRoutes(){
        $router = new Router();

        $router->addRoute('GET', '/', [HomeController::class, 'home']);
        $router->addRoute('GET', '/home', [HomeController::class, 'home']);

        $router->addRoute('GET', '/login', [HomeController::class, 'login']);
        $router->addRoute('GET', '/HomeUser', [HomeController::class, 'msHomeUser'], 'auth');


        $router->addRoute('GET', '/auth', [AuthController::class, 'authMsGraph']);
        $router->addRoute('GET', '/logout', [AuthController::class, 'logout']);
        $router->addRoute('POST', '/auth', [AuthController::class, 'authLocal']);

        return $router;
    }
}
