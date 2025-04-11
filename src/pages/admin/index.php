<?php
include_once 'utils/account_utils.php';
include_once "utils/db_utils.php";
include_once "utils/utils.php";
include_once "entities/models/result.php";

$url = $_SERVER['REQUEST_URI'];

if(!AccountUtils::is_signed_in() or !AccountUtils::is_admin()){
    include_once "pages/errors/403.php";
    die();
}
?>


<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/global/css/global.css">
    <link rel="stylesheet" href="/global/css/pages.css">
    <script src="/global/js/functions.js"></script>
    <title>Admin</title>
</head>
<body>
    <div class="main">
        <h1>Admin</h1>
    </div>
</body>
</html>