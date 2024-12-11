<?php
namespace Sk\App\Controllers;

use Sk\App\Core\MsGraph;
use Sk\App\Core\Middleware;
use Sk\App\Models\UserModel;
use Sk\App\Models\LogModel;
use Sk\App\Core\Utils;


class AuthController {
    private $msGraph;
    
    public function __construct(){
        $this->msGraph = new MsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
    }

    public function setSessionData($data){
        @$_SESSION["userData"] = $data;
        return $_SESSION;
    }

    public function authLocal(){
        $user = trim($_POST['usr']);
        $pass = trim($_POST['psw']);
        if ($user == "" || $pass == "" ) {
            print json_encode(array("error" => 1,"msg"=>"Ingresa tus datos de acceso"));
        } else {
            $um = new UserModel();
            $usrData = $um->getUser($user);
            if (!password_verify($pass, $usrData->upass)) {
                print json_encode(array("error" => 1,"msg"=>"Datos incorrectos"));
            } else {
                $data = array(
                    "idUser" => $usrData->id_usrsys,
                    "mail" => $usrData->email,
                    "name" => trim($usrData->nombre ?? '' . ' ') . trim($usrData->paterno ?? '' . ' ') . trim($usrData->materno ?? ''),
                    "freg" => $usrData->f_reg
                );
                $this->setSessionData($data);
                $middleware = new Middleware();
                $middleware->addMiddleware('auth');

                $ip_address = Utils::getUserIpAddress();
                $log = new LogModel();
                $log->logAction($usrData->id_usrsys, 2, 'Inicio sesion: ' . $usrData->email, $ip_address);
                $msHomeUser = Utils::cryptUri( SYSGLOBALKEY, 'HomeUser' );
                print json_encode(array("error" => 0,"msg"=>"Datos correctos","access"=>$msHomeUser));
            }
        }
    }

    public function authMsGraph(){
        //$this->msGraph = new MsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
        $userData = $this->msGraph->processAuth($_GET);
        $azureData = array(
            "idAzure" => $userData["id"],
            "mail" => $userData["mail"],
            "name" => $userData["displayName"],
            "puesto" => $userData["jobTitle"]
        );
        if ($userData["mail"]!="") {
            $usrMod = new UserModel();
            $lclUsr = $usrMod->getUser($azureData["mail"]);
            if ($lclUsr && $lclUsr->email == $userData["mail"]) {
                $data = array(
                    "idUser" => $lclUsr->id_usrsys,
                    "mail" => $lclUsr->email,
                    "name" => trim($lclUsr->nombre ?? '' . ' ') . trim($lclUsr->paterno ?? '' . ' ') . trim($lclUsr->materno ?? ''),
                    "freg" => $lclUsr->f_reg,
                    "azureData" => $azureData
                );
                $this->setSessionData($data);
                
                $middleware = new Middleware();
                $middleware->addMiddleware('auth');

                $ip_address = Utils::getUserIpAddress();
                $log = new LogModel();
                $log->logAction($lclUsr->id_usrsys, 2, 'Inicio sesion: ' . $lclUsr->email, $ip_address);
                $msHomeUser = Utils::cryptUri( SYSGLOBALKEY, 'HomeUser' );
                header("Location: $msHomeUser");
            }else {
                $this->logout();
            }
        } else {
            $this->logout();
        }
    }

    public function logout(){
        if (isset($_SESSION["userData"]["azureData"])) {
            $auth = new MsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
            $auth->logoutApp(APP_URL);
        } else {
            @session_start();
            @session_unset();
            @session_destroy();
            header("Location:" . APP_URL);
        }
        
    }
}
