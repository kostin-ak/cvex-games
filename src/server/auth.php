<?php

    include_once "../configs/config.php";
    include_once "../utils/db_utils.php";

    if (isset($_POST['login'])) {

        $query = DBUtils::getInstance()->users()->getByLoginOrEmail($_POST['login']);

        if ($query) {
            if (password_verify($_POST['password'], $query['password'])) {
                echo true;

                session_start();
                $_SESSION['user_uuid'] = $query['uuid'];
                $_SESSION['hash'] = password_hash($query['uuid'].$query['password'], PASSWORD_DEFAULT);
                $_SESSION['user'] = $query;

                return;
            }
        }
        echo false;
    }

    if (isset($_POST['logout'])) {
        session_start();
        unset($_SESSION['user_uuid']);
        unset($_SESSION['hash']);
        unset($_SESSION['user']);
    }


?>