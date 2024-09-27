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

        $requestUri = str_replace($subdir, '', $_SERVER['REQUEST_URI']);

        //error_log($requestUri);

        /*if($requestUri != '/'){
            @$datauri = explode("/", $_SERVER['REQUEST_URI']);
            if(count($datauri)>=2){
                @$dataUriPlain = explode("?", $datauri[1]);
                if(count($dataUriPlain)>=2){
                    @$showView  = $dataUriPlain[0];
                    @$cryptData = $dataUriPlain[1];
                } else {
                    @$cryptData = $datauri[3];
                    $dataView = Utils::base64Decrypt(SYSGLOBALKEY, $datauri[2]);
                    $svw = explode("|", $dataView);
                    $showView = (@$svw[1] == Utils::dFt())? @$svw[0]:'home';
                }
                @$GLOBALS['SHOWVIEW'] = $showView;
                @$GLOBALS['CRYPTDATA'] = $cryptData;
            }
        }*/
        

        $router->handleRequest($requestUri);
        //$view = new ShowView();
        //$view->renderView($showView, $cryptData);
        ob_end_flush();


    }
}
