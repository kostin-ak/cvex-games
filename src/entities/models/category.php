<?php

include_once __DIR__."/../../configs/config.php";
include_once ROOT."/entities/models/db_model.php";

class Categoty extends DBModel {
    private string $name;
    private string $description;
    private string $image;

    private bool $in_dev;

    private bool $is_public;
    public function __construct(string $uuid, string $name, string $description, string $image, bool $in_dev, bool $is_public){
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->in_dev = $in_dev;
        $this->is_public = $is_public;
    }
    public function getName(): string{
        return $this->name;
    }
    public function getDescription(): string{
        return $this->description;
    }
    public function getImage(): string{
        return $this->image;
    }

    public function isInDev(): bool{
        return $this->in_dev;
    }
    public function isIsPublic(): bool{
        return $this->is_public;
    }

    public static function fromData(array $data): self{
        return new Categoty($data['uuid'], $data['name'], $data['description'], $data['image'], $data['in_dev'], $data['is_public']);
    }


}