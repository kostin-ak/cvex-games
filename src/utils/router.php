<?php



class Router {

    protected array $url;
    protected string $surl;
    protected array $pages;

    function __construct(string $url, array $pages){
        if ($url != "" or $url != "/") $this->url = explode("/", $url);
        else $this->url = array();
        if ($url[strlen($url)-1] == "/") $url = substr($url, 0, -1);
        $this->surl = $url;
        $this->pages = $pages;
    }



    public function route(){

        $starts = false;

        foreach ($this->pages as $page){
            foreach ($page->urls as $url) {
                if (($url == "" and "" == $this->surl) or ($url != "" and preg_match($url, $this->surl))){
                    $page->start();
                    $starts = true;
                    break 2;
                }
            }
        }

        if (!$starts) {
            echo "<h1>ERROR</h1>";
            $page = Page::voidPage("pages/errors/404.php");
            $page->standard_page();
            $page->start();
        }

    }


    protected function str_starts_with($haystack, $needle, $case = true): bool
    {
        if ($case) {
            return strpos($haystack, $needle, 0) === 0;
        }
        return stripos($haystack, $needle, 0) === 0;
    }



}

?>