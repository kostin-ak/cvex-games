<?php
include_once 'utils/account_utils.php';
include_once "utils/db_utils.php";
include_once "utils/utils.php";
include_once "entities/models/result.php";

$url = $_SERVER['REQUEST_URI'];

if(!AccountUtils::is_signed_in() or !AccountUtils::is_admin()){
    include_once "pages/errors/403.php";
    function generate_tab(){

    }
}else{
    function generate_tab($url){
        include_once 'pages/admin/include/tabs.inc.php';
    }

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
    <link rel="stylesheet" href="/pages/admin/css/admin.css">
    <link rel="stylesheet" href="/pages/admin/css/message.css">
    <link rel="stylesheet" href="/pages/account/css/account.css">
    <script src="/pages/admin/js/message.js"></script>

</head>
<body>
    <div class="modal"></div>

    <div id="infoMessage" class="info-message">
        <div class="topic">
            <h2>Type</h2>
            <button class="close_message">x</button>
        </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquid atque, aut cumque facilis impedit nemo, nisi quam quod rerum similique ullam? Dolorum error eveniet sapiente vel! Facilis, possimus, totam!</p>
    </div>

    <div class="main">
        <div class="tabs">
            <?php
                generate_tab($url);
            ?>
        </div>
    </div>
</body>
</html>