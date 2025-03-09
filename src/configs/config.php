<?php

    define("ROOT",$_SERVER['DOCUMENT_ROOT']."/");

    include_once ROOT."/entities/models/permissions.php";

    class Config{
         public static string $APP_NAME = "cVEX";
         public static string $APP_ICON = "/global/images/favicon.svg";
         public static string $APP_FAVICON = "/global/images/favicon.svg";

         public static function getPermissions() :array{
             return array(
                 permissions_types::Admin => new Permission(0x01, "Admin", "Admin"),
                 permissions_types::AnswerWithoutRating => new Permission(0x02, "AWR", "Answer Without Rating")
             );
         }

         public static function getRoles() :array{
             return array(
                 new Role(0, "user", "Пользователь", 0, "user_class"),
                 new Role(1, "root", "Суперпользователь", getRootPermissions(), "root_class"),
                 new Role(1, "tester", "Тестировщик", getTesterPermissions(), "tester_class"),
             );
         }


    }
?>