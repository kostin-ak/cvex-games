<?php

function return_current_tab($url){
    switch($url){
        case "/admin":
        case "/admin/":
        case "/admin/home":
            return "home";
            break;
        case "/admin/task":
        case "/admin/task/":
            return "task";
            break;
        case "/admin/categories":
        case "/admin/categories/":
            return "categories";
            break;
        case "/admin/none":
        case "/admin/none/":
            return "none";
            break;
        default:
            return "home";
    }
}

function is_active($url, $tab){
    if(return_current_tab($url) == $tab) echo "active";
}

function get_tab($url){
    switch(return_current_tab($url)){
        case "home":
            include_once "pages/admin/include/tabs/home.inc.php";
            break;
        case "categories":
            include_once "pages/admin/include/tabs/categories.inc.php";
            break;
        case "task":
            include_once "pages/admin/include/tabs/tasks.inc.php";
            break;
    }
}

?>


<div class="tab_menu">
    <a href="/admin" class="tab <?php is_active($url, "home")?>"><span class="material-icons">dashboard</span><span class="tab-desc">Основная</span></a>
    <a href="/admin/categories" class="tab <?php is_active($url, "categories")?>"><span class="material-icons">category</span><span class="tab-desc">Категории</span></a>
    <a href="/admin/task" class="tab <?php is_active($url, "task")?>"><span class="material-icons">assignment</span><span class="tab-desc">Задания</span></a>
</div>
<div class="tab-content">
    <?php
        get_tab($url);
    ?>
</div>