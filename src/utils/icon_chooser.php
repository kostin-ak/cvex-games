<?php
include_once __DIR__."/../configs/config.php";
class IconChooser{
    static function chooseIcon($type){
        if (file_exists(ROOT."/global/images/file_icons/".$type.".png")) {
            return "/global/images/file_icons/".$type.".png";
        }else{
            return "/global/images/file_icons/_blank.png";
        }
    }
}