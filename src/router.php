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


    static function add_global_files(){
        echo "<style>";
        include_once "global/css/global.css";
        echo "</style>";
    }
}

?>