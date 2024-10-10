<?php
namespace Sk\App\Controllers;

use Sk\App\Core\MsGraph;
use Sk\App\Core\Middleware;
use Sk\App\Models\UserModel;
use Sk\App\Models\RegistroModel;
use Sk\App\Models\LogModel;
use Sk\App\Core\Utils;


class AuthController {
    public function __construct(){}

    public function authMsGraph(){
        $auth = new MsGraph();
        $userData = $auth->processAuth($_GET);

        if ($userData["mail"]!="") {
            $middleware = new Middleware();
            $middleware->addMiddleware('auth');
            header("Location: protected");
        }

        //$usrModel = new UserModel();
        //$lclUsr = $usrModel->getUser($userData["mail"]);

        /*if ($lclUsr && $lclUsr->mail_user == $userData["mail"]) {
            $idUsr = $lclUsr->id_mail_user;
            $data = array(
                "idUser" => $idUsr,
                "mail" => $lclUsr->mail_user,
                "name" => $lclUsr->name_user,
                "freg" => $lclUsr->f_reg,
                "AZdata" => $userData
            );
            $this->setSessionData($data);

            $ip_address = Utils::getUserIpAddress();
            $log = new LogModel();
            $log->logAction($_SESSION["userData"]["idUser"], 2, '', 'Inicio sesion ' . $_SESSION["userData"]["mail"], $ip_address);
            header("Location: HomeUser");

        } else {
            $auth->logoutApp();
        }*/
    }

    public function logout(){
        @session_start();
        @session_unset();
        @session_destroy();
        $urlLogout = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?';
        $urlLogout.= 'post_logout_redirect_uri=' . urlencode(APP_URL);
        $urlLogout.= '&client_id=' . AZ_CLIENT_ID;
        header("Location: $urlLogout");
    }
}
