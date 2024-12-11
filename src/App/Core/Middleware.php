<?php
namespace Sk\App\Core;

class Middleware {
    public function __construct(){
    }

    public function handle($middlewareName, $callback){
        if ($this->hasMiddleware($middlewareName)){
            if (is_callable($callback)) {
                $callback();
            }
        } else {
            $this->handleInvalidMiddleware();
        }
    }

    public function hasMiddleware($middlewareName){
        return isset($_SESSION['middlewares'][$middlewareName]);
    }

    public function addMiddleware($middlewareName, $value = true){
        $_SESSION['middlewares'][$middlewareName] = $value;
    }

    public function removeMiddleware($middlewareName){
        unset($_SESSION['middlewares'][$middlewareName]);
    }

    protected function handleInvalidMiddleware(){
        header('Location:' . APP_URL);
        exit();
    }
}
