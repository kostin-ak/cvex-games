<?php

    include_once ROOT."/entities/models/user.php";
    include_once ROOT."/entities/models/permissions.php";
    include_once ROOT."/utils/db_utils.php";

    class AccountUtils{
        static function is_signed_in(){
            session_start();
            return isset($_SESSION['user_uuid']) and $_SESSION['user_uuid'] != null;
        }
        static function is_admin(){

            if (AccountUtils::is_signed_in()) {
                $user = AccountUtils::get_user();
                return Permissions::testIntPermission($user->getRole()->getPermissions(), Permissions::getPermission(permissions_types::Admin));
            }
            return false;
        }

        static function get_user(){
            session_start();
            if(isset($_SESSION['user_uuid']) and $_SESSION['user_uuid'] != null){
                $user = DBUtils::getInstance()->users()->getUserByUuid($_SESSION['user_uuid']);
                $_SESSION['user'] = $user;
                return User::fromData($_SESSION['user']);
            }else{
                return false;
            }
        }
    }




?>