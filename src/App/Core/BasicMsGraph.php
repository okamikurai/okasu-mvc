<?php
/**
 **  @author Okami
 */
namespace Sk\App\Core;

class MicrosoftGraphAuth {
    private $tenantId;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tokenUrl;
    private $accessToken;

    public function __construct($tenantId, $clientId, $clientSecret, $redirectUri ) {
        $this->tenantId = $tenantId;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->tokenUrl = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";
    }

    public function getAuthorizationUrl(){
        $params = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'response_mode' => 'query',
            'scope' => 'User.Read',
            'state' => session_id()
        ]);
        return "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/authorize?" . $params;
    }

    // Obtener el token de acceso usando el código de autorización
    public function getAccessToken($authorizationCode)
    {
        $postData = http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode,
            'redirect_uri' => $this->redirectUri,
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded",
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 200 && $httpCode < 300) {
            $tokenData = json_decode($response, true);
            $this->accessToken = $tokenData['access_token'];
            return $this->accessToken;
        } else {
            throw new Exception("Error al obtener el token. Código HTTP: $httpCode");
        }
    }

    // Hacer una petición a Microsoft Graph
    private function makeGraphRequest($url, $parseJson = true)
    {
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
            throw new Exception("Error en la petición a Microsoft Graph. Código HTTP: $httpCode");
        }
    }

    public function getUserInfo()
    {
        $url = "https://graph.microsoft.com/v1.0/me";
        return $this->makeGraphRequest($url);
    }

    // Obtener la foto del perfil del usuario autenticado
    public function getAuthenticatedUserProfilePhoto()
    {
        $url = "https://graph.microsoft.com/v1.0/me/photo/\$value";
        return $this->makeGraphRequest($url, false);  // La respuesta es binaria (imagen)
    }
}
