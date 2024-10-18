<?php
namespace Sk\App\Models;

use Sk\App\Core\Pgdb;

class UserModel extends Pgdb {
    public function getUser($email){
        try {
            $sql = "SELECT * FROM usrsys WHERE ( lo_tri_tra(email) = lo_tri_tra($1) OR lo_tri_tra(uname) = lo_tri_tra($1) ) AND activo IS TRUE;";
            $usrData = $this->prepQuery($sql, array($email));
            if (pg_num_rows($usrData)>0) {
                return pg_fetch_object($usrData);
            } else{
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function listUsers($limit, $offset, $activo = 0){
        $condicion = "";
        $condicion = ($activo == 1 ) ? "AND activo IS TRUE" : ( ($activo == 2) ? "AND activo IS FALSE" : "" );
        try {
            $sql = "SELECT * FROM mail_users_auth WHERE id_mail_user > 0 $condicion LIMIT $1 OFFSET $2;";
            $usrData = $this->prepQuery($sql, array($limit, $offset));
            if (pg_num_rows($usrData)>0) {
                return pg_fetch_all($usrData);
            } else{
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getCountUsers($activo = 0){
        $condicion = "";
        $condicion = ($activo == 1 ) ? "AND activo IS TRUE" : ( ($activo == 2) ? "AND activo IS FALSE" : "" );
        try {
            $sql = "SELECT COUNT(id_mail_user)as numreg FROM mail_users_auth WHERE id_mail_user > 0 $condicion ;";
            $q = $this->query($sql);
            if (pg_num_rows($q)>0) {
                $r = pg_fetch_assoc($q);
                return $r["numreg"];
            } else{
                return 0;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function addUser($usrDt){
        try {
            $sql = "INSERT INTO mail_users_auth (mail_user,name_user,fk_id_user_registro) VALUES ($1, $2, $3) RETURNING *;";
            $usrData = $this->prepQuery($sql, array($usrDt["mail"], $usrDt["name"], -1));
            if (pg_num_rows($usrData)>0) {
                return pg_fetch_object($usrData);
            } else{
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
