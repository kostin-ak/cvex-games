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
        Page::standardPageRegexp("pages/admin/index.php", ["/\/admin/"]),
        Page::standardPageRegexp("pages/account/account.php", ["/\/account/"]),
        Page::standardPage("pages/account/radar_chart.php", ["/account/radar"]),
        Page::standardPage("pages/tasks/category/index.php", ["/category"]),
        Page::standardPageRegexp("pages/tasks/category/category.php", ["/\/category?/"]),
        Page::standardPage("pages/tasks/tasks/index.php", ["/tasks"]),
        Page::standardPageRegexp("pages/tasks/tasks/index.php", ["/\/tasks\?/"]),
        Page::standardPageRegexp("pages/tasks/tasks/task.php", ["/\/task/"]),
        Page::standardPageRegexp("pages/rating/index.php", ["/\/rating/"]),
        Page::standardPage("pages/info/about.php", ["/about"]),
        Page::standardPage("pages/info/ctf.php", ["/ctf"]),
        Page::standardPage("pages/info/contacts.php", ["/contacts"]),
        Page::standardPage("pages/info/privacy.php", ["/privacy"]),
        Page::standardPage("pages/info/terms.php", ["/terms"]),
    );

    function get_pages(): array
    {
        global $pages;
        return $pages;
    }

?>