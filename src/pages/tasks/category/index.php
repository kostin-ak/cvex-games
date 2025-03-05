<?php

    include_once "entities/models/category.php";
    include_once "pages/tasks/category/category_item.php";

    $categories = array(
        new Categoty("1", "Test1", "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet eos facilis illo impedit laudantium
                maxime molestiae, mollitia nobis perferendis possimus quae quam reiciendis sed tempore veritatis vero.
                Dolor, ea.", "https://imgholder.ru/512x512/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson", false),
        new Categoty("2", "Test2", "Test category", "https://imgholder.ru/512x512/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson", false),
        new Categoty("3", "Test3", "Test category", "https://imgholder.ru/512x512/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson", false),
        new Categoty("4", "Test4", "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet eos facilis illo impedit laudantium
                maxime molestiae, mollitia nobis perferendis possimus quae quam reiciendis sed tempore veritatis vero.
                Dolor, ea.", "https://imgholder.ru/512x512/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson", true),
        new Categoty("5", "Test5", "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet eos facilis illo impedit laudantium
                maxime molestiae, mollitia nobis perferendis possimus quae quam reiciendis sed tempore veritatis vero.
                Dolor, ea.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet eos facilis illo impedit laudantium
                maxime molestiae, mollitia nobis perferendis possimus quae quam reiciendis sed tempore veritatis vero.
                Dolor, ea.", "https://imgholder.ru/512x512/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson", false),
        new Categoty("6", "Test6", "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias amet eos facilis illo impedit laudantium
                maxime molestiae, mollitia nobis perferendis possimus quae quam reiciendis sed tempore veritatis vero.
                Dolor, ea.", "https://imgholder.ru/512x512/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson", false),
    );

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="global/css/global.css">
    <link rel="stylesheet" href="global/css/pages.css">
    <link rel="stylesheet" href="pages/tasks/category/css/category.css">
    <script src="/global/js/functions.js"></script>
    <title>Категории</title>
</head>
<body>
<div class="main">
    <div class="categories">
        <?php
            foreach ($categories as $category) {
                echo category_item($category);
            }
        ?>
    </div>
</div>
</body>
</html>