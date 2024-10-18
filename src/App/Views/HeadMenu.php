<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a></li>
            <li class="nav-item d-none d-md-block"> <a href="/" class="nav-link">Inicio</a></li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="data:image/jpeg;base64,<?=$userImage?>" class="user-image rounded-circle shadow" alt="">
                    <span class="d-none d-md-inline"><?=$user["name"]?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-dark">
                        <img src="data:image/jpeg;base64,<?=$userImage?>" class="rounded-circle shadow" alt="">
                        <p>
                            <?=$user["name"]?>
                            <small><?=$user["mail"]?></small>
                        </p>
                    </li>
                    <li class="user-body">
                    </li>
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Perfil</a>
                        <a href="logout" class="btn btn-outline-secondary btn-flat float-end"><i class="bi bi-box-arrow-right"></i> Salir</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
