<?php
namespace Sk\App\Views;

?><!doctype html>
<html lang="es" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <title>
            <?php
            global $titleHeader;
            global $pathAssets;
            print $titleHeader
            ?>
        </title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        <link href="https://framework-gb.cdn.gob.mx/gm/v4/image/favicon.ico" rel="shortcut icon">

        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

        <script src="https://kit.fontawesome.com/8a90b77b5c.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <link href="<?=$pathAssets?>/css/headers.css" rel="stylesheet" />
    </head>
    
<body>
    <div id="lockscreen" class="blockscreen">
        <h4 class="display-5 lsmesg"><i class="fa fa-cog fa-spin fa-3x fa-fw" aria-hidden="true"></i> Espere por favor ...</h4>
    </div>
    <header>
        <nav class="navbar navbar-dark bg-gmx-gr">
            <div class="container-fluid ">
                <a class="navbar-brand p-0 w-50 h-auto">
                    <img src="<?=$pathAssets?>/img/logoGob.svg" alt="" class="p-0 w-25 h-auto" >
                </a>
            </div>
        </nav>
    </header>

<main>
<div style="display: flex; justify-content: flex-start;">
    <div class="container-fluid">
