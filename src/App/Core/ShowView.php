<?php
namespace Sk\App\Core;

class ShowView {
    private $fileView = "Home";
    private $Error404 = "Error404";
    
    public function __construct(){
    }

    public function renderView(string $fileView = "", ){
        $utils = new Utils();
        $home = $utils->base64Crypt(SYSGLOBALKEY, $this->fileView . '|' . $utils->dFt());
        $fileView = ($fileView != "") ? $fileView : $home;
        $dataView = $utils->base64Decrypt(SYSGLOBALKEY, $fileView);
        $svw = explode("|", $dataView);
        if(@$svw[1] == $utils->dFt()){
            $this->fileView = @$svw[0];
        } else {
            $this->fileView = $fileView;
        }
        $this->showview();
    }

    public function showview(){
        $path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . 'views') .DIRECTORY_SEPARATOR;
        $fileInclude = $this->fileView . '.php';
        if (file_exists($path . $fileInclude)){
            return include_once $path . $fileInclude;
        } else{
            return include_once $path . $this->Error404 . ".php";
        }
    }

    public static function render($fileView = "", $data = array()){
        $path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . 'views') .DIRECTORY_SEPARATOR;
        $fileView = $fileView!='' ? $path . $fileView . ".php" : $path . "Error404.php";
        return require_once $fileView;
    }

    
}


