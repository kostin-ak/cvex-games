<?php
    include_once "utils/router.php";
    include_once "entities/page.php";

    $pages = array(
        Page::standardPage("pages/errors/501.php", []),
        Page::standardPage("pages/main/index.php", ["/", "/home", "/main"]),
        Page::standardPage("pages/account/login.php", ["/login"]),
        Page::standardPage("pages/account/signup.php", ["/signup"]),
        Page::standardPage("pages/errors/404.php", []),
        Page::standardPage("pages/errors/403.php", ["/errors/403"]),
        Page::standardPage("pages/admin/index.php", ["/admin"]),
    );

    function get_pages(): array
    {
        global $pages;
        return $pages;
    }

?>