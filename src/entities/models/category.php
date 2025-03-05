<?php

class Categoty{
    private string $uuid;

    private string $name;
    private string $description;
    private string $image;

    private bool $in_dev;
    public function __construct(string $uuid, string $name, string $description, string $image, bool $in_dev){
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->in_dev = $in_dev;
    }

    public function getUuid(): string{
        return $this->uuid;
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

    public static function fromData(array $data): Categoty{
        return new Categoty($data['uuid'], $data['name'], $data['description'], $data['image'], $data['in_dev']);
    }
}