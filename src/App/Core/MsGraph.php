<?php
namespace Sk\App\Core;
/**
 ** @author Okami
 ** Requiere permisos en azure : User.Read.All, ProfilePhoto.Read.All
 */
class MsGraph {
    private $tenantId;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tokenUrl;
    private $authorizationCode;
    private $accessToken;
    private $refreshToken;
    public $urlMsonline = "https://login.microsoftonline.com";
    public $urlMsGraph = "https://graph.microsoft.com";

    public function __construct($tenantId, $clientId, $clientSecret, $redirectUri ){
        if (session_status() == PHP_SESSION_NONE) { @session_start(); }
        $this->tenantId = $tenantId;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->tokenUrl = "{$this->urlMsonline}/{$this->tenantId}/oauth2/v2.0/token";
        if (isset($_SESSION["AZ"]["authorizationCode"])) {
            $this->setAutorizationCode($_SESSION["AZ"]["authorizationCode"]);
        }
        if (isset($_SESSION["AZ"]["accessToken"])) {
            $this->setAccessToken($_SESSION["AZ"]["accessToken"]);
        }
        if (isset($_SESSION["AZ"]["refreshToken"])) {
            $this->setRefreshToken($_SESSION["AZ"]["refreshToken"]);
        }
    }

    public function setAutorizationCode($authorizationCode){
        $this->authorizationCode = $authorizationCode;
        $_SESSION["AZ"]["authorizationCode"] = $authorizationCode;
    }

    public function setAccessToken($accessToken){
        $this->accessToken = $accessToken;
        $_SESSION["AZ"]["accessToken"] = $accessToken;
    }

    public function setRefreshToken($refreshToken){
        $this->refreshToken = $refreshToken;
        $_SESSION["AZ"]["refreshToken"] = $refreshToken;
    }

    public function getAuthorizationUrl(){
        $postData = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'response_mode' => 'query',
            'redirect_uri' => $this->redirectUri,
            'scope' => 'User.Read',
            'state' => session_id(),
            'approval_prompt' => 'auto'
        ]);
        return "{$this->urlMsonline}/{$this->tenantId}/oauth2/v2.0/authorize?" . $postData;
    }

    public function getAccesToken($authorizationCode){
        $postData = http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode,
            'scope' => "{$this->urlMsGraph}/.default offline_access",
            'redirect_uri' => $this->redirectUri,
        ]);
        $headers = ["Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($postData)];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errCurl = curl_error($curl);
        curl_close($curl);
        if ($httpCode >= 200 && $httpCode < 300) {
            $tokenData = json_decode($response, true);
            if (isset($tokenData["error"])) {
                throw new \Exception("Bearer token fetch contained an error");
            }
            $this->setAccessToken($tokenData['access_token']);
            $this->setRefreshToken($tokenData['refresh_token']);
            return $this->accessToken;
        } else {
            throw new \Exception("Error al obtener el token. Código HTTP: {$httpCode} - {$errCurl}");
        }
    }

    public function getAccessTokenAZ(){
        $postData = http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => "{$this->urlMsGraph}/.default offline_access",
        ]);
        $headers = ["Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($postData)];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => $headers
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpCode >= 200 && $httpCode < 300) {
            $tokenData = json_decode($response, true);
            if (isset($tokenData["error"])) {
                throw new \Exception("Bearer token fetch contained an error");
            }
            $this->setAccessToken($tokenData['access_token']);
            $this->refreshToken($tokenData['refresh_token']);
            return $this->accessToken;
        } else {
            throw new \Exception("Error al obtener el token. Código HTTP: $httpCode");
        }
    }

    private function makeGraphRequest($url, $parseJson = true){
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->accessToken,
                "Content-Type: application/json",
            ],
        ]);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            return $parseJson ? json_decode($response, true) : $response;
        } else {
            throw new \Exception("Microsoft Graph. Error: {$httpCode}");
        }
    }

    public function renewAccessToken() {
        $postData = http_build_query([
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ]);
        //CURLOPT_HTTPHEADER => $headers,
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 200 && $httpCode < 300) {
            $tokenData = json_decode($response, true);
            if (isset($tokenData['access_token'])) {
                $this->setAccessToken($tokenData['access_token']);
                if (isset($tokenData['refresh_token'])) {
                    $this->setRefreshToken($tokenData['refresh_token']);
                }
                return $this->accessToken;
            }else {
                throw new Exception('Error al obtener el nuevo access token: ' . $response);
            }
        } else {
            throw new Exception("Error al obtener el token. Código HTTP: $httpCode");
        }
    }

    public function processAuth($request = []){
        if (!isset($request["code"]) && !isset($request["error"])) {
            $url = $this->getAuthorizationUrl();
            header("Location: {$url}");
            exit;
        } elseif (isset($request["error"])) {
            throw new Exception($request["error"]);
        } elseif (strcmp(session_id(), $request["state"]) == 0) {
            $this->setAutorizationCode($request["code"]);
            $this->accessToken = $this->getAccesToken($this->authorizationCode);
            $userdata = $this->getMeUserInfo();
            if (isset($userdata["error"])) {
                throw new \Exception($userdata["error"]);
            }
            return $userdata;
        }
    }

    public function simpleImgProfile(){
        $im = @imagecreatetruecolor (128, 128) or die ("Cannot Initialize new GD image stream");
        $bg = imagecolorallocatealpha ($im, 255, 255, 255, 127);
        $clr = ImageColorAllocate ($im, 0, 73, 153);
        imagefill($im, 1, 1, $bg);
        imagefilledellipse($im, 64, 64, 128, 128, $clr);
        imagesavealpha($im, true);
        ob_start();
        imagepng($im);
        $final_image = ob_get_contents();
        imagedestroy($im);
        ob_end_clean();
        return $final_image;
    }

    public function getMeUserInfo(){
        $url = "{$this->urlMsGraph}/v1.0/me";
        return $this->makeGraphRequest($url);
    }

    public function getMeUserProfilePhoto(){
        $url = "{$this->urlMsGraph}/v1.0/me/photo/\$value";
        return $this->makeGraphRequest($url, false);
    }

    public function getUserInfo($email){
        $url = "{$this->urlMsGraph}/v1.0/users/{$email}";
        return $this->makeGraphRequest($url);
    }

    public function getUserProfilePhoto($email){
        $url = "{$this->urlMsGraph}/v1.0/users/{$email}/photo/\$value";
        return $this->makeGraphRequest($url, false);
    }

    public function logoutApp($logoutUrl){
        @session_start();
        @session_unset();
        @session_destroy();

        $postData = http_build_query([
            'post_logout_redirect_uri' => $logoutUrl,
            'client_id' => $this->clientSecret,
        ]);
        $urlLogout = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?' . $postData;
        header("Location: $urlLogout");
    }
}
