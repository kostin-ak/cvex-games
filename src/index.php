<?php

    include_once "configs/config.php";
    include_once "utils/router.php";
    include_once "pages.php";
    include_once "utils/database.php";
    include_once "global/blocks/preinclude.php";

    session_start();

	$uri = $_SERVER['REQUEST_URI'];
    $router = new Router($uri, get_pages());
    $router->route();


?>

