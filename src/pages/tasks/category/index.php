<?php

    include_once "entities/models/category.php";
    include_once "pages/tasks/category/category_item.php";

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/main/home.css">
    <link rel="stylesheet" href="/pages/tasks/category/css/category.css">
    <title>Категории</title>
</head>
<body>
<div class="main">
    <div class="left_menu">

    </div>
    <div class="categories">
        <?php
            category_template();
        ?>
    </div>
</div>
</body>
</html>