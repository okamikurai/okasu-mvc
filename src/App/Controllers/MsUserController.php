<?php
namespace Sk\App\Controllers;

use Sk\App\Core\Utils;
use Sk\App\Core\MsGraph;
use Sk\App\Models\UserModel;


class MsUserController {
    private $msGraph;

    public function __construct(){
        $this->msGraph = new MsGraph(AZ_AD_TENANT, AZ_CLIENT_ID, AZ_CLIENT_SECRET, AZ_REDIRECT_URI);
    }

    public function checkSession(){
        if (!isset($_SESSION["userData"])) {
            $this->msGraph->logoutApp(APP_URL);
            return false;
        }
        return true;
    }

    public function getMeProfile(){
        $usAz = $this->msGraph->getMeUserInfo();
        $aD = array(
            "idAzure" => $usAz["id"],
            "mail" => $usAz["mail"],
            "name" => $usAz["displayName"],
            "puesto" => $usAz["jobTitle"]
        );
        $usr = new UserModel();
        $usrDb = $usr->getUser($usAz["mail"]);
        $data = array(
            "idUser" => $usrDb->id_usrsys,
            "mail" => $usrDb->email,
            "name" => trim($usrDb->nombre ?? '' . ' ') . trim($usrDb->paterno ?? '' . ' ') . trim($usrDb->materno ?? ''),
            "freg" => $usrDb->f_reg,
            "azureData" => $aD
        );
        return $data;
    }

    public function getMeImgProfile(){
        try {
            $userimg = $this->msGraph->getMeUserProfilePhoto();
        } catch (\Throwable $th) {
            $userimg = $this->msGraph->simpleImgProfile();
        }
        $userimg = base64_encode($userimg);
        return $userimg;
    }
}
