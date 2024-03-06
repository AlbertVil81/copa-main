<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo("template_directory"); ?>/assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php bloginfo("template_directory"); ?>/assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo("template_directory"); ?>/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php bloginfo("template_directory"); ?>/assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo("template_directory"); ?>/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php bloginfo("template_directory"); ?>/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php bloginfo("template_directory"); ?>/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title><?php bloginfo('name'); ?></title>

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <?php wp_head(); ?>
</head>
<body>
    <nav style="background-color: #BB007A !important;">
        <div class="nav-wrapper">
        <a href="<?php bloginfo("url"); ?>/" class="brand-logo">Copa Termal</a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="<?php bloginfo("url"); ?>/">Inicio</a></li>
            <li><a href="<?php bloginfo("url"); ?>/patrocinadores/">Patrocinadores</a></li>
            <li><a href="<?php bloginfo("url"); ?>/equipos/">Equipos</a></li>
            <li><a href="<?php bloginfo("url"); ?>/calendario/">Calendario</a></li>
        </ul>
        </div>
    </nav>
    <ul class="sidenav" id="mobile-demo">
        <div class="links">
            <div class="space10"></div>
            <div class="centered">
                <!--<img src="<?php bloginfo("template_directory"); ?>/assets/img/redline-logo.png" alt="" class="responsive-img sac-login">-->
                <p>Copa termal</p>
            </div>
            <div class="space10"></div>
            <li><a href="<?php bloginfo("url"); ?>/">Inicio</a></li>
            <li><a href="<?php bloginfo("url"); ?>/patrocinadores/">Patrocinadores</a></li>
            <li><a href="<?php bloginfo("url"); ?>/equipos/">Equipos</a></li>
            <li><a href="<?php bloginfo("url"); ?>/calendario/">Calendario</a></li>
        </div>
    </ul>


    