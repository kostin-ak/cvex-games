<?php

    class AccountUtils{
        static function is_signed_in(){
            session_start();
            return isset($_SESSION['user_uuid']) and $_SESSION['user_uuid'] != null;
        }
        static function is_admin(){
            return isset($_SESSION['user_id2']);
        }
    }




?>