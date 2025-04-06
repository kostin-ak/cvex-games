<?php

include_once "entities/models/category.php";
include_once "utils/account_utils.php";
include_once "configs/config.php";
include_once "utils/db_utils.php";


function category_item(Categoty $category){

    $name = $category->isInDev()?"InDEV":$category->getName();
    $uuid = $category->isInDev()?"in-dev":$category->getUuid();
    $description = $category->isInDev()?"
        Эта категория сайта в настоящее время находится в разработке. Мы работаем над тем, чтобы предоставить вам лучший контент и функционал. Пожалуйста, загляните позже, чтобы узнать о наших обновлениях. Спасибо за ваше терпение!
    ":$category->getDescription();
    $image = $category->isInDev()?"/pages/tasks/category/images/question.svg":$category->getImage();
    $image_block = ($image!=""and$image!="null")?"<div class=\"category-image\"><img src=\"".$image."\"alt=\"".$name."\"></div>":"";
    $is_public = $category->isIsPublic();

    $value = "
        <a class='card".($category->isInDev()?" in-dev":" ")."' href=\"?uuid=".$uuid."\">
            <div class=\"category\">
                    
                <div class=\"category-text\">
                    <h1>".$name."</h1>
                    <p>".$description."</p>
                </div>
                ".$image_block."
            </div>
        </a>
    ";
    if(!$is_public and !AccountUtils::is_signed_in()){
        return "";
    }
    return $value;

}

function category_template(){

    $query = DBUtils::getInstance()->categories()->getList();

    foreach ($query as $category) {
        $category_obj = Categoty::fromData($category);
        echo category_item($category_obj);
    }
}