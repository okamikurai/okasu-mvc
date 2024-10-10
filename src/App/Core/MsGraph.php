<?php
namespace Sk\App\Core;

class MsGraph {
    public $urlMsonline = "https://login.microsoftonline.com/";
    public $urlMsGraph = "https://graph.microsoft.com/";
    
    public function errorHandler($input){
        $output = "PHP Session ID:    " . session_id() . PHP_EOL;
        $output .= "Client IP Address: " . getenv("REMOTE_ADDR") . PHP_EOL;
        $output .= "Client Browser:    " . $_SERVER["HTTP_USER_AGENT"] . PHP_EOL;
        ob_start();
        error_log(json_encode($input));
        $output .= ob_get_contents();
        ob_end_clean();
        error_log($output);
        return false;
    }

    public function redirectAuth(){
        $url = $this->urlMsonline . AZ_AD_TENANT . "/oauth2/v2.0/authorize?";
        $url .= "state=" . session_id();
        $url .= "&scope=User.Read";
        $url .= "&response_type=code";
        $url .= "&approval_prompt=auto";
        $url .= "&client_id=" . AZ_CLIENT_ID;
        $url .= "&redirect_uri=" . urlencode(AZ_REDIRECT_URI);
        header("Location: " . $url);
    }

    public function getTokenAZ(){
        $url = $this->urlMsonline . AZ_AD_TENANT . "/oauth2/v2.0/token";
        $headers = ["Content-Type: application/x-www-form-urlencoded"];

        $params = "client_id=" . AZ_CLIENT_ID;
        $params .= "&scope=" . urlencode("https://graph.microsoft.com/.default");
        $params .= "&client_secret=" . AZ_CLIENT_SECRET;
        $params .= "&grant_type=client_credentials";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => $headers,
        ));
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            self::errorHandler(array(
                "Description" => "Error received during Bearer token fetch.",
                "PHP_Error" => error_get_last(),
                "curl ERR" => curl_error($clr)
            ));
        } else {
            $ad = json_decode($response, true);
        }
        curl_close($curl);

        if (isset($ad["error"])){
            self::errorHandler(array("Description" => "Bearer token fetch contained an error.","\$ad[]" => $ad));
            return false;
        }
        return $ad["access_token"];
    }

    public function codeAuthAZ($code_request){
        $url = $this->urlMsonline . AZ_AD_TENANT . "/oauth2/v2.0/token";
        
        $content = "grant_type=authorization_code&client_id=" . AZ_CLIENT_ID;
        $content .= "&redirect_uri=" . urlencode(AZ_REDIRECT_URI);
        $content .= "&code=" . $code_request;
        $content .= "&client_secret=" . urlencode(AZ_CLIENT_SECRET);

        $headers = ["Content-Type: application/x-www-form-urlencoded","Content-Length: " . strlen($content)];
        
        $clr = curl_init($url);
        curl_setopt($clr, CURLOPT_POST, true);
        curl_setopt($clr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($clr, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($clr, CURLOPT_POSTFIELDS, $content);
        $response = curl_exec($clr);

        if (curl_errno($clr)) {
            self::errorHandler(array("Description" => "Error received during Bearer token fetch.", "PHP_Error" => error_get_last(), "curl ERR" => curl_error($clr)));
        } else {
            $authdata = json_decode($response, true);
        }
        curl_close($clr);

        if (isset($authdata["error"])){
            self::errorHandler(array("Description" => "Bearer token fetch contained an error.", "\$authdata[]" => $authdata));
            return false;
        }
        return $authdata;
    }

    public function getUserdataAZ($accessToken){
        $url = $this->urlMsGraph . "v1.0/me";
        $headers = ["Accept: application/json","Authorization: Bearer " . $accessToken ];

        $clr = curl_init($url);
        curl_setopt($clr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($clr, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($clr);
        if (curl_errno($clr)) {
            error_log('Error en cURL: ' . curl_error($clr));
            return false;
        } elseif ($response === false) {
            error_log("Error en solicitud");
            return false;
        }
        
        $json = json_decode($response, true);
        curl_close($clr);

        return $json;
    }

    public function dataUserAzure($email, $accessToken){
        $url = $this->urlMsGraph . "v1.0/users/" . $email;
        $headers = ["Accept: application/json","Authorization: Bearer " . $accessToken ];

        $clr = curl_init($url);
        curl_setopt($clr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($clr, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($clr);
        if (curl_errno($clr)) {
            error_log('Error en cURL: ' . curl_error($clr));
            return false;
        } elseif ($response === false) {
            error_log("Error en la solicitud");
            return false;
        }
        $json = json_decode($response, true);
        curl_close($clr);
        return $json;
    }

    public function getImageUser($userId, $accessToken){
        $url = $this->urlMsGraph . "v1.0/users/$userId/photo/\$value";
        $headers = ["Accept: application/json", "Authorization: Bearer " . $accessToken ];

        $clr = curl_init($url);
        curl_setopt($clr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($clr, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($clr);
        $http_status = curl_getinfo($clr, CURLINFO_HTTP_CODE);
    
        if($http_status != 200){
            error_log("Sin imagen");
            return false;
        } elseif (curl_errno($clr) || $response === false) {
            $msgErr = !curl_errno($clr)?"Error en la solicitud":"Error en cURL: ".curl_error($clr);
            error_log($msgErr);
            return false;
        }
        curl_close($clr);
        return $response;
    }

    public function processAuth($request = []){
        if (!isset($request["code"]) && !isset($request["error"])) {
            self::redirectAuth();
            exit;
        } elseif (isset($request["error"])) {
            self::errorHandler(array("Description" => "Error received at the beginning of second stage.", "\$request[]" => $request, "\$_SESSION[]" => $_SESSION));
            return false;
        } elseif (strcmp(session_id(), $request["state"]) == 0) {
            $_SESSION["AZ"]["code"]=$request["code"];
            $code_request = $request["code"];
            $authdata = self::codeAuthAZ($code_request);
            $userdata = self::getUserdataAZ($authdata["access_token"]);
            if (isset($userdata["error"])) {
                self::errorHandler(array("Description" => "User data fetch contained an error.", "\$userdata[]" => $userdata, "\$authdata[]" => $authdata, "\$request[]" => $request));
                return false;
            }
            return $userdata;
        }
    }

    public function logoutApp(){
        @session_start();
        @session_unset();
        @session_destroy();
        $urlLogout = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?';
        $urlLogout.= 'post_logout_redirect_uri=' . urlencode(APP_URL);
        $urlLogout.= '&client_id=' . AZ_CLIENT_ID;
        header("Location: $urlLogout");
    }

    public static function checkSession(){
        if (!isset($_SESSION["userData"])) {
            self::logoutApp();
            exit(0);
        }
        return true;
    }

    public static function chkSessAs(){
        if (!isset($_SESSION["userData"])) {
            return array("error"=>"2","msgErr" =>"No se puede procesar la solicitud recargue la página");
        }
        return array("error"=>"0");
    }
}
