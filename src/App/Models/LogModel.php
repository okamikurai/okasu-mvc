<?php
namespace Sk\App\Models;

use Sk\App\Core\Pgdb;

class LogModel extends Pgdb {
    public function logAction($idUsr, $id_cat_log, $log_desc, $ip_address){
        try {
            $sql = "INSERT INTO log_users(fk_id_usrsys, fk_id_cat_log, log_desc, ip_address) VALUES ($1, $2, $3, $4);";
            $this->prepQuery($sql, array($idUsr, $id_cat_log, $log_desc, $ip_address));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
