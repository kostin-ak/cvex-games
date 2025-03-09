<?php
class Page{

    protected string $path;
    protected bool $add_global_styles = false;
    protected bool $add_menu = false;
    protected bool $add_footer = false;
    protected bool $add_includes = true;
    public array $urls = [];

    protected function __construct(string $path){
        $this->path = $path;
    }

    public function start(){

        if ($this->add_includes){
            include_once "global/blocks/includes.php";
        }
        if (file_exists($this->path)) require_once $this->path;
        else require_once "pages/errors/501.php";

        if ($this->add_menu){
            require_once "global/blocks/menu/menu.php";
        }

        if ($this->add_global_styles){
            echo "<style>";
            include_once "global/css/global.css";
            echo "</style>";
        }

        if ($this->add_footer){
            require_once "global/blocks/footer/footer.php";
        }


    }

    public function standard_page(){
        $this->add_global_styles = true;
        $this->add_menu = true;
        $this->add_footer = true;
    }

    public static function standardPage(string $path, array $urls){
        $instance = new Page($path);
        $instance->standard_page();
        for ($i = 0; $i < count($urls); $i++){
            $urls[$i] = str_replace("/", "\/", $urls[$i]);
            if ($urls[$i][strlen($urls[$i])-1] == "/") $urls[$i] = substr($urls[$i], 0, -1);
            if ($urls[$i] != "\\") $urls[$i] = "/^" . $urls[$i] . "$/";
            else $urls[$i] = "";

        }
        $instance->urls = $urls;
        return $instance;
    }

    public static function standardPageRegexp(string $path, array $urls){
        $instance = new Page($path);
        $instance->standard_page();
        $instance->urls = $urls;
        return $instance;
    }

    public static function voidPage($path){
        $instance = new Page($path);
        $instance->standard_page();
        return $instance;
    }
}
?>