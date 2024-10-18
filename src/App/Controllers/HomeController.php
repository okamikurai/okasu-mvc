<?php
namespace Sk\App\Controllers;

use Sk\App\Core\ShowView;
use Sk\App\Core\Utils;

class HomeController {


    public function home(){
        $loginUrl = Utils::cryptUri( SYSGLOBALKEY, '/login' );
        $authUrl = Utils::cryptUri( SYSGLOBALKEY, '/auth' );
        
        $data = ["sistema" => "Sistema de ", "login"=> $loginUrl, "auth"=> $authUrl ];
        ShowView::render('Home', $data);
    }

    public function msHomeUser(){
        $user = new MsUserController();
        $user->checkSession();
        $usrPict = $user->getMeImgProfile();
        $data = array(
            "userData" => $_SESSION["userData"],
            "userImage" => $usrPict
        );
        ShowView::render('HomeUser', $data);
        
    }

    public function login(){
        $data = array(
            "postUri" => Utils::cryptUri( SYSGLOBALKEY, '/auth' )
        );
        ShowView::render('Login',$data);
    }
}
