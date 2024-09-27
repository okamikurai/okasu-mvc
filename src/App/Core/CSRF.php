<?php
namespace Sk\App\Core;

class CSRF {
    private static $tokenName = 'csrf_token';

    public static function generateToken():string {
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::$tokenName] = $token;
        return $token;
    }

    public static function validateToken($token):bool {
        if (isset($_SESSION[self::$tokenName]) && $_SESSION[self::$tokenName] === $token) {
            unset($_SESSION[self::$tokenName]);
            return true;
        }
        return false;
    }
}
