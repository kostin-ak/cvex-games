<?php

    include_once "entities/menu_items.php";
    include_once "utils/account_utils.php";
    include_once "configs/config.php";

    $menu_pages = array(
        MenuItem::add("Главная", "/"),
        MenuItem::add("Страница 1", "#page1", false),
        MenuItem::add("Страница 2", "#page2"),
        MenuItem::add("Страница 3", "#page3"),
        MenuItem::add("Страница 4", "#page4"),
        MenuItem::add("Админ-панель", "/admin", AccountUtils::is_admin())
    );

    function print_account(){
        if (!AccountUtils::is_signed_in()){
            echo '<a class="menu-login-button" href="/login">Вход</a>';
        }else{
            echo '<a class="menu-login-button account" href="/account">
                    <img src="global/images/account.svg" alt="">
                </a>';
        }
    }

    function print_menu($pages){
        foreach ($pages as $page){
            if ($page->visible) {
                $classes = "menu-item";
                if ($page->active) $classes = "active menu-item";
                echo '<a class="' . $classes . '" href="' . $page->link . '">' . $page->title . '</a>';
            }
        }
    }

?>


<head>
    <link rel="stylesheet" href="global/blocks/menu/menu.css">
</head>

<body>

<div class="menu">
    <div class="mobile-action noselect">
        <div id="nav-icon" class="noselect">
            <span class="noselect"></span>
        </div>
    </div>

    <div class="sub-menu logo">
        <div class="icon">
            <img src="<?php echo Config::$APP_ICON?>" alt="">
        </div>
        <a href="/" class="platform_name">
            <?php echo Config::$APP_NAME?>
        </a>
    </div>
    <div class="sub-menu links">
        <ul class="menu-buttons">
            <?php print_menu($menu_pages);?>
        </ul>
        <?php print_account(); ?>
    </div>
</div>

<div class="mobile-menu">
    <ul class="mobile-menu-buttons">
        <?php print_menu($menu_pages);?>
        <a class="menu-item" onclick="darkMode()">Сменить тему</a>
    </ul>
</div>

<script>
    function darkMode() {
        document.body.classList.toggle('dark-theme');
    }
    $(".mobile-action").on("click", function (){
        $("#nav-icon").toggleClass('open');
        if ($(".mobile-menu").hasClass("active")){
            $(".mobile-menu").animate({
                top:-1000,
            }, 500, function() {
                $(".mobile-menu").removeClass("active")
            });
        }else{
            $(".mobile-menu").addClass("active")
            $(".mobile-menu").animate({
                top:0,
            }, 500, function() {
                // Animation complete.
            });
        }
    })
</script>

</body>


