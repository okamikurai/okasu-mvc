<?php
namespace Sk\App\Controllers;

use Sk\App\Core\ShowView;
use Sk\App\Core\Utils;

class HomeController {

    public function home(){
        $login = '/login' . '|' . Utils::dFt();
        $auth = '/auth' . '|' . Utils::dFt();
        $loginUrl = Utils::base64Crypt( SYSGLOBALKEY, $login );
        $authUrl = Utils::base64Crypt( SYSGLOBALKEY, $auth );
        
        $data = ["sistema"=>"Sistema de ", "login"=> $loginUrl, "auth"=> $authUrl ];
        ShowView::render('Home', $data);
    }

    public function homeUser(){
        echo "Esta es una ruta protegida, solo accesible con middleware!";
    }

    public function login(){
        ShowView::render('Login');
    }
}
