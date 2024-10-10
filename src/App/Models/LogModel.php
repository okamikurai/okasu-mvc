<?php
namespace Sk\App\Models;

use Sk\App\Core\Pgdb;

class LogModel extends Pgdb {
    public function logAction($idUsr, $id_cat_bitacora, $folio, $obs, $ip_address){
        try {
            $sql = "INSERT INTO bitacora_users(fk_id_mail_user, fk_id_cat_bitacora, folio, obs, ip_address) VALUES ($1, $2, $3, $4, $5);";
            $this->prepQuery($sql, array($idUsr, $id_cat_bitacora, $folio, $obs, $ip_address));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
