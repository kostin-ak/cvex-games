<?php

class Router {

    protected array $url;

    function __construct(string $url){
        if ($url != "" or $url != "/") $this->url = explode("/", $url);
        else $this->url = array();
    }

    public function route(){
        switch($this->url[1]){
            case "index.php":
            case "home":
            case "":
                RouterRules::main_page();
                break;

            case "login":
                RouterRules::login();
                break;
            case "signup":
                RouterRules::signup();
                break;

            default:
                RouterRules::error404();
                break;
        }
    }


}

class RouterRules{
    static function main_page(){
        self::add_global_files();
        require_once "pages/main/index.php";
    }

    static function error404(){
        self::add_global_files();
        require_once "pages/errors/404.php";
    }

    static function login(){
        self::add_global_files();
        require_once "pages/account/login.php";
    }

    static function signup(){
        self::add_global_files();
        require_once "pages/account/signup.php";
    }


    static function add_global_files(){
        echo "<style>";
        include_once "global/css/global.css";
        echo "</style>";
    }
}

?>