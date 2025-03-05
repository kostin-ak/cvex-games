<?php

include_once "entities/models/category.php";

function category_item(Categoty $category){

    $name = $category->isInDev()?"InDEV":$category->getName();
    $uuid = $category->isInDev()?"in-dev":$category->getUuid();
    $description = $category->isInDev()?"
        Эта категория сайта в настоящее время находится в разработке. Мы работаем над тем, чтобы предоставить вам лучший контент и функционал. Пожалуйста, загляните позже, чтобы узнать о наших обновлениях. Спасибо за ваше терпение!
    ":$category->getDescription();
    $image = $category->isInDev()?"/pages/tasks/category/images/question.svg":$category->getImage();

    $value = "
        <a class='card".($category->isInDev()?" in-dev":" ")."' href=\"?uuid=".$uuid."\">
            <div class=\"category\"><img src=\"".$image."\"
                                            alt=\"\">
                <h1>".$name."</h1>
                <p>".$description."</p>
            </div>
        </a>
    ";
    return $value;
}