<?php

    include_once "entities/menu_items.php";
    include_once "utils/account_utils.php";
    include_once "configs/config.php";

    $menu_pages = array(
        MenuItem::add("Главная", "/"),
        MenuItem::add("Категории", "/category"),
        MenuItem::add("Задания", "/tasks"),
        MenuItem::add("Рейтинг", "/rating"),
        MenuItem::add("Админ-панель", "/admin", AccountUtils::is_admin())
    );

    function print_account(){
        if (!AccountUtils::is_signed_in()){
            echo '<a class="menu-login-button" href="/login">Вход</a>';
        }else{
            echo '<a class="menu-login-button account">
                    <img src="/global/images/account.svg" alt="">
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

    <script>
        $(window).on('load', function() {
            if (Cookies.get('dark') == "true"){
                $(".theme-switcher").text("☼");
            }else{
                $(".theme-switcher").text("☾");
            }
        });
    </script>
</head>

<body id="swup">

<div class="account_menu">
    <ul>
        <li onclick="window.location.href = '/account';">Аккаунт</li>
        <hr>
        <li class="logout"><img src="/global/images/logout.svg"/><span>Выйти</span></li>
    </ul>
</div>

<div class="menu">
    <div class="mobile-action noselect">
        <div id="nav-icon" class="noselect">
            <span class="noselect"></span>
        </div>
    </div>

    <div class="sub-menu logo">

        <a href="/" class="platform_name">
            <div class="icon">
                <img src="<?php echo Config::$APP_ICON?>" alt="">
            </div>
            <?php echo Config::$APP_NAME?>
        </a>
    </div>
    <div class="sub-menu links">
        <ul class="menu-buttons">
            <?php print_menu($menu_pages);?>
        </ul>
        <?php print_account(); ?>
        <a class="theme-switcher noselect" onclick="darkMode()"></a>
    </div>
</div>

<div class="mobile-menu">
    <ul class="mobile-menu-buttons">
        <?php print_menu($menu_pages);?>
        <hr>
        <a class="menu-item" onclick="darkMode()">Сменить тему</a>
    </ul>
</div>

<script>
    function darkMode() {
        document.body.classList.toggle('dark-theme');
        Cookies.set('dark', $("body").hasClass("dark-theme"));
        if (Cookies.get('dark') == "true"){
            $(".theme-switcher").text("☼");
        }else{
            $(".theme-switcher").text("☾");
        }
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
                top:100,
            }, 500, function() {
                // Animation complete.
            });
        }
    })

    $(".menu-login-button.account").on("click",function (){
        if ($(".account_menu").hasClass("active")){
            $(".account_menu").animate({
                top:-10,
                opacity:-1
            }, 200, function() {
                $(".account_menu").removeClass("active")
            });
        }else{
            $(".account_menu").addClass("active")
            $(".account_menu").animate({
                top:83,
                opacity:1
            }, 200, function() {
                // Animation complete.
            });
        }
    });
</script>

</body>


