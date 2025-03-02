<?php

    class AccountUtils{
        static function is_signed_in(){
            return isset($_SESSION['user_id']);
        }
        static function is_admin(){
            return isset($_SESSION['user_id2']);
        }
    }




?>