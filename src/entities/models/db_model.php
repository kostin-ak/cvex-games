<?php
    abstract class DBModel{
        public $uuid;
        function __construct($uuid){
            $this->uuid = $uuid;
        }

        abstract public static function fromData(array $array);
    }
?>