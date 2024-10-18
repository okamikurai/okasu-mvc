<?php
namespace Sk\App\Views;
?>
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark" aria-label="sidebar">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <a href="#" class="brand-link">
            <img src="<?=$pathAssets?>/img/logoGob.svg" alt="" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">Title</span>
        </a>
    </div>
    <!--end::Sidebar Brand-->
    
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false" >
                <li class="nav-item menu-open">
                    <a href="HomeUser" class="nav-link active"><i class="bi bi-houses"></i><p>Inicio</p></a>
                </li>

                <?=$SideBarOpts?>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
