<?php
namespace Sk\App\Core;

class ShowView {
    private $fileView = "Home";
    private $error404 = "Error404";
    
    public function __construct(){
    }

    /*public function renderView(string $fileView = ""){
        $home = Utils::cryptUri( SYSGLOBALKEY, $this->fileView );
        $fileView = ($fileView != "") ? $fileView : $home;
        $dataView = Utils::base64Decrypt(SYSGLOBALKEY, $fileView);
        $svw = explode("|", $dataView);
        if(@$svw[1] == Utils::dFt()){
            $this->fileView = @$svw[0];
        } else {
            $this->fileView = $fileView;
        }
        $this->showview();
    }

    public function showview(){
        $path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . 'Views') .DIRECTORY_SEPARATOR;
        $fileInclude = $this->fileView . '.php';
        if (file_exists($path . $fileInclude)){
            return include_once $path . $fileInclude;
        } else{
            return include_once $path . $this->error404 . ".php";
        }
    }*/

    public static function render($fileView = "", $data = array()){
        $path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . 'Views') .DIRECTORY_SEPARATOR;
        $fileView = $fileView!='' ? $path . $fileView . ".php" : $path . "error404.php";
        return require_once $fileView;
    }

    
}


