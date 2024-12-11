<?php
namespace Sk\App\Controllers;

use Sk\App\Core\ShowView;
use Sk\App\Core\Middleware;
use Sk\App\Core\Utils;

class HomeController {


    public function home(){
        $md = new Middleware();
        if ($md->hasMiddleware('auth')) {
            header("Location: " . Utils::cryptUri( SYSGLOBALKEY, '/HomeUser' ));
            exit;
        }
        $loginUrl = Utils::cryptUri( SYSGLOBALKEY, '/login' );
        $authUrl = Utils::cryptUri( SYSGLOBALKEY, '/auth' );
        
        $data = ["sistema" => "Sistema de ", "login"=> $loginUrl, "auth"=> $authUrl ];
        ShowView::render('Home', $data);
    }

    public function msHomeUser(){
        $user = new MsUserController();
        $user->checkSession();
        ShowView::render('HomeUser');
        
    }

    public function login(){
        $data = array(
            "postUri" => Utils::cryptUri( SYSGLOBALKEY, '/auth' )
        );
        ShowView::render('Login',$data);
    }
}
