<?php
    abstract class DBModel{
        public $uuid;
        function __construct($uuid){
            $this->uuid = $uuid;
        }

        public function getUuid(): string{
            return $this->uuid;
        }

        abstract public static function fromData(array $array);
    }
?>