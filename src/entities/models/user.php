<?php

include_once __DIR__."/../../configs/config.php";
include_once ROOT."/entities/models/db_model.php";

include_once ROOT."entities/models/roles.php";

class User extends DBModel{
    private string $username;
    private string $name;

    private string $sname;

    private string $email;
    private string $password;

    private role $role;

    private int $score;

    private $registered;

    private int $group;

    public function __construct($uuid, $username, $name, $sname, $email, $password, $role, $score, $registered, $group) {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->name = $name;
        $this->sname = $sname;
        $this->email = $email;
        $this->password = $password;
        $this->role = Role::getRuleById($role);
        $this->score = $score;
        $this->registered = $registered;
        $this->group = $group;
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
    public function getScore(): int{
        return $this->score;
    }

    public function getRegistered(){
        return $this->registered;
    }

    public static function fromData($array) {
        return new User($array["uuid"], $array['username'], $array['name'], $array['sname'],$array['mail'], $array['password'], $array['role'], $array['score'], $array['registered'], $array['group']);
    }



}