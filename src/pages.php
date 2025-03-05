<?php
    include_once "utils/router.php";
    include_once "entities/page.php";

    $pages = array(
        Page::standardPage("pages/errors/501.php", []),
        Page::standardPage("pages/main/index.php", ["/", "/home", "/main"]),
        Page::standardPageRegexp("pages/account/login.php", ["/\/login/"]),
        Page::standardPage("pages/account/signup.php", ["/signup"]),
        Page::standardPage("pages/errors/404.php", []),
        Page::standardPage("pages/errors/403.php", ["/errors/403"]),
        Page::standardPage("pages/admin/index.php", ["/admin"]),
        Page::standardPage("pages/account/account.php", ["/account"]),
        Page::standardPage("pages/tasks/category/index.php", ["/category"]),
        Page::standardPageRegexp("pages/tasks/category/category.php", ["/\/category?/"]),
        Page::standardPage("pages/tasks/tasks/index.php", ["/tasks"]),
        Page::standardPage("pages/rating/index.php", ["/rating"]),
    );

    function get_pages(): array
    {
        global $pages;
        return $pages;
    }

?>