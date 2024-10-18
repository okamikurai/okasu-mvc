<?php
namespace Sk\App\Controllers;

use Sk\App\Core\Utils;
use Sk\App\Models\SidebarModel;

class SidebarController {
    public function __construct(){
    }

    public static function getUserMenu($idUsr){
        $sm = new SidebarModel();
        $modules = $sm->getModules($idUsr);
        if (!$modules) {
            return "";
        }
        return self::renderMenu($modules);
    }

    public static function renderMenu($menus, $parent_id = null){
        $html = '';
        foreach ($menus as $menu) {
            if ($menu['parent_id_sys_am'] == $parent_id) {
                $url = ($menu['app_mod']=="#") ? "#" : Utils::cryptUri( SYSGLOBALKEY, $menu['app_mod'] );
                $navArrow = ($menu['app_mod']=="#") ? '<i class="nav-arrow bi bi-chevron-right"></i>' : "";
                $html .= '<li class="nav-item">';
                $html .= '<a href="'.$url.'" class="nav-link"><i class="nav-icon '.$menu['icon'].'"></i><p>'.$menu['app_mod_lbl']. $navArrow .'</p></a>';
                $subMenu = array_filter($menus, function($m) use ($menu) {
                    return $m['parent_id_sys_am'] == $menu['id_sys_am'];
                });
                if (!empty($subMenu)) {
                    $html .='<ul class="nav nav-treeview ms-3">';
                    $html .= self::renderMenu($menus, $menu['id_sys_am']);
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
        }
        return $html;
    }
}
