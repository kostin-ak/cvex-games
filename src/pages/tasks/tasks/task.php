<?php
include_once 'utils/account_utils.php';
include_once "utils/db_utils.php";
include_once "utils/utils.php";

if(!AccountUtils::is_signed_in()){
    header("Location: /login?link=$_SERVER[REQUEST_URI]", true, 307);
    die();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <div class="main"></div>
</body>
</html>