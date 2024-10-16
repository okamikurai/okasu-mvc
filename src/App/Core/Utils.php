<?php
namespace Sk\App\Core;

class Utils {
    public function __construct(){ // Constructor - empty
    }

    public static function dataCrypt($keySa, $data){
        $method = 'AES-256-CBC';
        $iv = substr(hash('sha256', $keySa), 0, 16);
        return @openssl_encrypt ($data, $method, $keySa, 0, $iv);
    }

    public static function dataDecrypt($keySa, $data){
        $method = 'AES-256-CBC';
        $iv = substr(hash('sha256', $keySa), 0, 16);
        return @openssl_decrypt($data,$method, $keySa, 0, $iv);
    }

    public static function base64Crypt($keySa, $data){
        return base64_encode(self::dataCrypt($keySa, $data));
    }

    public static function base64Decrypt($keySa, $data){
        return self::dataDecrypt($keySa, base64_decode($data));
    }

    public static function dFt(string $format = 'Ymd'){
        return date($format);
    }

    public static function cryptUri($keySa, $uri){
        return self::base64Crypt($keySa, $uri . '|' .self::dFt());
    }

    public static function decryptUri($keySa, $uri){
        $res = self::base64Crypt($keySa, $uri);
        return explode("|",$res)[0];
    }

    public static function getUserIpAddress() {
        $ip = "";
        $ipArr = [
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        foreach ($ipArr as $key) {
            if ( array_key_exists( $key, $_SERVER ) ) { //  existe la clave en $_SERVER
                foreach ( array_map( 'trim', explode( ',', $_SERVER[ $key ] ) ) as $ip ) {
                    if ( filter_var( $ip, FILTER_VALIDATE_IP  ) !== false ) { // | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                        $ips = explode(",", trim($ip));
                        if (is_array($ips)){
                            $ip = $ips[0];
                        }
                        return $ip;
                    }
                }
            }
        }
        return $ip;
    }

    public static function fileProcUpload($filePost, $pixelRed = 1024, $rdi = true){
        $fl_error  = $_FILES[$filePost]['error'];
        if ($fl_error > 0) {
            switch ($fl_error) {
                case UPLOAD_ERR_INI_SIZE:
                    $err_fl = "El archivo excede el tamaño permitido."; // 1: Upload excede la directiva upload_max_filesize en php.ini.
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $err_fl = "El archivo excede el tamaño especificado."; // 2: Upload excede la directiva MAX_FILE_SIZE que fue especificada en el form HTML.
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $err_fl = "El archivo fue sólo parcialmente cargado, intente de nuevo."; //3: Upload fue sólo parcialmente cargado.
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $err_fl = "No se especifico ningún archivo."; // 4: Ningún archivo fue subido.
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $err_fl = "Error en carpeta temporal."; // 6: Falta la carpeta temporal | permisos?
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $err_fl = "No se pudo guardar el archivo."; // 7: No se pudo escribir el archivo en el disco.
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $err_fl = "Error en el servidor."; // 8: Una extensión de PHP detuvo la carga de archivos.
                    break;
                default:
                    $err_fl = "Error desconocido."; // Error desconocido.
                    break;
            }

            $fileRet = array("name"=>"","type"=>"","tmp_name"=>"","size"=>"","extension"=>"","error"=>$err_fl,"err"=>1);
            
        } else {

            $a_name = $_FILES[$filePost]['name'];
            $a_type = $_FILES[$filePost]['type'];
            $a_tmp  = $_FILES[$filePost]['tmp_name'];
            $a_size = $_FILES[$filePost]['size'];
            $a_ext  = strtolower(pathinfo($_FILES[$filePost]['name'], PATHINFO_EXTENSION));
            
            if ($rdi) {
                /*
                    Valida que tipo de archivo es para procesarlo
                    Cualquier tipo de imagen será redimensionada y convertida a jpg
                */
                $imgProp = getimagesize($a_tmp);
                if (!$imgProp) {
                    $imgImageWidth = $imgProp[0];
                    $imgImageHeight= $imgProp[1];
                    $imgImageType  = $imgProp[2];

                    $resizeWidth = $pixelRed; /* Tamaño de la redimension*/
                    $ratio_orig = $imgImageWidth/$imgImageHeight;
                    $resizeHeight = $resizeWidth/$ratio_orig;

                    /*Procesa imagen*/
                    $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
                    switch ($imgImageType) {
                        case IMAGETYPE_JPEG:
                            $resourceType = imagecreatefromjpeg($a_tmp);
                        break;
                        case IMAGETYPE_GIF:
                            $resourceType = imagecreatefromgif($a_tmp);
                        break;
                        case IMAGETYPE_PNG:
                            $resourceType = imagecreatefrompng($a_tmp);
                            $fillColor=imagecolorallocate($imageLayer, 255, 255, 255);
                            imagefill($imageLayer,0,0,$fillColor);
                        break;
                        default:
                            $resourceType = imagecreatefromjpeg($a_tmp);
                        break;
                    }

                    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $imgImageWidth,$imgImageHeight);
                    imagejpeg($imageLayer,$a_tmp,100); // aqui se sobreescribe el archivo de imagen en el temporal
                    ImageDestroy($resourceType);
                    ImageDestroy($imageLayer);
                    $a_type = "image/jpeg";
                    $a_size = filesize($a_tmp);
                    $a_ext  = "jpeg";
                    $a_name = pathinfo($a_name,PATHINFO_FILENAME).".".$a_ext;
                }
            }

            $fileRet = array(
                "name"     => $a_name,
                "type"     => $a_type,
                "tmp_name" => $a_tmp,
                "size"     => $a_size,
                "extension"=> $a_ext,
                "error"    => $fl_error,
                "err"      => 0
            );
        }
        return $fileRet;
    }

    public static function getJsonPost(){
        $contentType = explode(';', $_SERVER['CONTENT_TYPE']);
        $rawBody = file_get_contents("php://input");
        $data = array();

        if(in_array('application/json', $contentType)) {
            $data = json_decode($rawBody);
        } else {
            parse_str($data, $data);
        }
        return $data;
    }

    public static function clearurl($uri){
        $uri = substr($uri, 1);
        $uri = str_replace(DIRECTORY_EMP.'/','',$uri);
        return $uri;
    }

    public static function genStrRand($length = 10) {
        return substr(str_shuffle(str_repeat($x='23456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    public static function genPassrand(){
        $pattern = strtoupper(trim('23456789ASDFGHJKPUYTREWQMZNXBCV'));
        $max = strlen($pattern) - 1;
        $key = "";
        for ($i = 0; $i < 9; $i++) {
            $key .= $pattern[mt_rand(1, $max)];
        }
        return trim($key);
    }

    public static function getEstadoId($lit){
        $literales = array(
            "AS" => 1, "BC" => 2, "BS" => 3, "CC" => 4, "CL" => 5, "CM" => 6, "CS" => 7, "CH" => 8, "DF" => 9, "DG" => 10,
            "GT" => 11, "GR" => 12, "HG" => 13, "JC" => 14, "MC" => 15, "MN" => 16, "MS" => 17, "NT" => 18, "NL" => 19, "OC" => 20,
            "PL" => 21, "QT" => 22, "QR" => 23, "SP" => 24, "SL" => 25, "SR" => 26, "TC" => 27, "TS" => 28, "TL" => 29, "VZ" => 30, "YN" => 31, "ZS" => 32, "NE" =>53
        );
        if (array_key_exists($lit, $literales)){
            return $literales[$lit];
        } else {
            return 0;
        }
    }

    public static function getLiteralCurp($idEdo){
        $idEstados = array(
            1 => "AS", 2 => "BC", 3 => "BS", 4 => "CC", 5 => "CL", 6 => "CM", 7 => "CS", 8 => "CH", 9 => "DF", 10 => "DG",
            11 => "GT", 12 => "GR", 13 => "HG", 14 => "JC", 15 => "MC", 16 => "MN", 17 => "MS", 18 => "NT", 19 => "NL", 20 => "OC",
            21 => "PL", 22 => "QT", 23 => "QR", 24 => "SP", 25 => "SL", 26 => "SR", 27 => "TC", 28 => "TS", 29 => "TL", 30 => "VZ", 31 => "YN", 32 => "ZS", 53 => "NE"
        );
        if (array_key_exists($idEdo, $idEstados)){
            return $idEstados[$idEdo];
        } else{
            return "";
        }
    }
}
