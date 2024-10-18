<?php
namespace Sk\App\Models;
use Sk\App\Core\Pgdb;

class SidebarModel extends Pgdb {
    public function getModules($idUsr){
        try {
            $sql = "SELECT s.id_sys_am,s.app_mod,s.app_mod_lbl,s.icon,s.parent_id_sys_am,s.order_mod
                FROM usr_sys_am u
                LEFT JOIN sys_app_mod s ON s.id_sys_am = u.fk_id_sys_am
                WHERE u.fk_id_usrsys = $1
                ORDER BY s.parent_id_sys_am NULLS FIRST, s.order_mod;";

            $SideBarOpts = $this->prepQuery($sql, array($idUsr));
            if (pg_num_rows($SideBarOpts)>0) {
                return pg_fetch_all($SideBarOpts);
            } else{
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
