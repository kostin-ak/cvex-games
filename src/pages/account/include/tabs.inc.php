<?php

    function return_current_tab($url){
        switch($url){
            case "/account":
            case "/account/":
            case "/account/home":
                return "home";
                break;
            case "/account/activity":
            case "/account/activity/":
                return "activity";
                break;
            case "/account/profile":
            case "/account/profile/":
                return "profile";
                break;
            case "/account/none":
            case "/account/none/":
                return "none";
                break;
            case "/account/achievements":
                return "achievements";
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
                include_once "pages/account/include/tabs/home.inc.php";
                break;
            case "activity":
                include_once "pages/account/include/tabs/activity.inc.php";
                break;
            case "achievements":
                include_once "pages/account/include/tabs/achievements.inc.php";
                break;
        }
    }

?>


<div class="tab_menu">
    <a href="/account" class="tab <?php is_active($url, "home")?>"><span class="material-icons">home</span><span class="tab-desc">Основная информация</span></a>
    <a href="/account/activity" class="tab <?php is_active($url, "activity")?>"><span class="material-icons">analytics</span><span class="tab-desc">Активности</span></a>
    <a href="/account/achievements" class="tab <?php is_active($url, "achievements")?>"><span class="material-icons">emoji_events</span><span class="tab-desc">Достижения</span></a>
</div>
<div class="tab-content">
    <?php
        get_tab($url);
    ?>
</div>