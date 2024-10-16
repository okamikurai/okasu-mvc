<?php
/**
 *  @since 20240708
 *  @author Okami
 **/
namespace Sk\App\Core;

use Sk\App\Core\Config;
use Sk\App\Core\Utils;
use Sk\App\Routes\WebRoutes;

//use Sk\App\Core\ShowView;

class App {
    public function __construct(){
        if (session_status() == PHP_SESSION_NONE) {@session_start();}
        Config::loadConf();

        global $titleHeader;
        global $pathAssets;
        global $baseurl;
        global $subdir;
        global $showView;
        global $cryptData;

        $titleHeader = 'Sistema';     // Head title
        $pathAssets  = '/assets';     // Path Assets
        $baseurl     = '';            // base url
        $subdir      = '';            // vacio por default con "/" al final
        $showView    = 'home';        // View inicial
        $cryptData   = '';            // inicializa variable de datos

        $router = WebRoutes::getRoutes();
        $requestUri = self::decodeUriRoute($subdir, $_SERVER['REQUEST_URI']);

        $router->handleRequest($requestUri);
        ob_end_flush();
    }

    private static function decodeUriRoute($subdir, $requestUri){
        $pathUri = parse_url($requestUri, PHP_URL_PATH);
        
        $uri = str_replace($subdir, '', $pathUri);
        $res = "/";
        if($uri != '/'){
            @$datauri = explode("/", $pathUri);
            @$res = $datauri[1];
            /*if(count($datauri)>=2){
                @$dataUriPlain = explode("?", $datauri[1]);
                if(count($dataUriPlain)>1){
                    @$res = $dataUriPlain[0];
                }
            }*/
            @$res = (!Utils::base64Decrypt(SYSGLOBALKEY, $res)) ? $res : Utils::base64Decrypt(SYSGLOBALKEY, $res);
            @$res = explode('|', $res)[0];
            if (substr($res,0,1)!='/') {
                $res = '/' . $res;
            }
        }
        
        return $res;
    }
}
