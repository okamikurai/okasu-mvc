<?php
/**
 *  @since 20201101
 *  @author Okami
 **/
namespace Sk\App\Core;
class Config {
    public function __construct(){
    }
    
    public static function loadConf($fileConf = "config.php"){
        $path = realpath(dirname(__FILE__) . '/../../../') . DIRECTORY_SEPARATOR;
        require_once $path . $fileConf;
    }
}
