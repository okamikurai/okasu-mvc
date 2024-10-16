<?php
namespace Sk\App\Controllers;

use Sk\App\Core\Utils;
use Sk\App\Core\MsGraph;

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
        return $this->msGraph->getMeUserInfo();
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
