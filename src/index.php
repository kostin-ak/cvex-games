<?php

    include_once "utils/router.php";
    include_once "pages.php";

	$uri = $_SERVER['REQUEST_URI'];

    $router = new Router($uri, get_pages());
    $router->route();


?>