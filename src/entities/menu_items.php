<?php
    class MenuItem{
        public string $title;
        public string $link;

        public bool $active;

        public bool $visible;

        private function __construct(string $title, string $link){
            $this->title = $title;
            $this->link = $link;
        }

        public static function add(string $title, string $link, bool $visible  = true, bool $active = false){
            $menu = new MenuItem($title, $link);

            $uri = $_SERVER['REQUEST_URI'];
            if ( ($menu->link == "/" and $uri == "/") or ($menu->link != "/" and  strpos($uri, $menu->link) !== false)) {
                $menu->active = true;
            }
            else if ($active){
                $menu->active = true;
            }
            else{
                $menu->active = false;
            }
            $menu->visible = $visible;
            return $menu;
        }
    }
?>