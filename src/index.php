<?php

    include_once "router.php";

	$uri = $_SERVER['REQUEST_URI'];
    $router = new Router($uri);
    $router->route();


?>