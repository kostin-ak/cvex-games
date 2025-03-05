<?php

include_once "entities/models/roles.php";

class User {
    private string $uuid;
    private string $username;
    private string $name;

    private string $sname;

    private string $email;
    private string $password;

    private role $role;

    public function __construct($uuid, $username, $name, $sname, $email, $password, $role) {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->name = $name;
        $this->sname = $sname;
        $this->email = $email;
        $this->password = $password;
        $this->role = Role::getRuleById($role);
    }

    public function getUuid(): string {
        return $this->uuid;
    }
    public function getUsername(): string {
        return $this->username;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getSname(): string {
        return $this->sname;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getPassword(): string {
        return $this->password;
    }
    public function getRole(): Role {
        return $this->role;
    }

    public static function getUserByArray($array) {
        return new User($array["uuid"], $array['username'], $array['name'], $array['sname'],$array['mail'], $array['password'], $array['role']);
    }

}