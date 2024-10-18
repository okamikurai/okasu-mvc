<?php
namespace Sk\App\Views;
use Sk\App\Controllers\SidebarController;

global $titleHeader;
global $pathAssets;

$user = $data["userData"];
$userImage = $data["userImage"];

$SideBarOpts =SidebarController::getUserMenu($user['idUser']);
?><!doctype html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titleHeader ?></title>

    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="<?=$pathAssets?>/img/favicon.ico" rel="shortcut icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
    
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="<?=$pathAssets?>/css/app.css">
</head>
<body class="layout-fixed-complete sidebar-expand-lg sidebar-mini bg-body-tertiary app-loaded">
    <div id="lockscreen" class="blockscreen" style="padding-top: 15%;display: none;">
        <h2 class="text-light"><i class="fas fa-spinner fa-spin"></i>&nbsp; Cargando ...</h2>
    </div>

    <div class="app-wrapper">
    <?php include_once dirname(__FILE__) . "/HeadMenu.php";?>
    <?php include_once dirname(__FILE__) . "/SideBar.php";?>

<main class="app-main">

