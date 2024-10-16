<?php
namespace Sk\App\Controllers;

use Sk\App\Core\MsGraph;
use Sk\App\Core\BasicMsGraph;
use Sk\App\Core\Middleware;
use Sk\App\Models\UserModel;
use Sk\App\Models\RegistroModel;
use Sk\App\Models\LogModel;
use Sk\App\Core\Utils;


class AuthController {
    public function __construct(){}

    public function setSessionData($data){
        @$_SESSION["userData"] = $data;
        return $_SESSION;
    }

    public function authMsGraph(){
        $msAuth = new MsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
        $userData = $msAuth->processAuth($_GET);

        if ($userData["mail"]!="") {
            $middleware = new Middleware();
            $middleware->addMiddleware('auth');
            $data = array(
                "idAzure" => $userData["id"],
                "mail" => $userData["mail"],
                "name" => $userData["displayName"],
                "puesto" => $userData["jobTitle"]
            );
            $this->setSessionData($data);

            $msHomeUser = Utils::cryptUri( SYSGLOBALKEY, 'msHomeUser' );
            header("Location: $msHomeUser");
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
        $auth = new MsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
        $auth->logoutApp();
    }

    public function authMsBasic(){
        $auth = new BasicMsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
        $userData = $auth->processAuth($_GET);

        if ($userData["mail"]!="") {
            $middleware = new Middleware();
            $middleware->addMiddleware('auth');
            header("Location: protected");
        }
    }
}
