<?php
namespace Sk\App\Controllers;

use Sk\App\Core\ShowView;

class HomeController {

    public function home(){
        $data = ["welcome"=>"Text Welcome"];
        ShowView::render('Home', $data);
        //require_once __DIR__ . '/../Views/Home.php';
    }

    public function homeUser(){
        echo "Esta es una ruta protegida, solo accesible con middleware!";
    }

    public function login(){
        ShowView::render('Login');
    }
}
