<?php

class permissions_types{
    public const Admin = "admin";
    public const AnswerWithoutRating = "awr";
}


class Permission{
    private int $id;
    private string $name;
    private string $description;

    public function __construct($id, $name, $description){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getDescription(): string{
        return $this->description;
    }


    public static function or($v1, $v2) : Permission{
        return new Permission($v1->id | 2, $v2->id, $v1->name." или ".$v2->name, "комбинация ИЛИ");
    }

    public static function and($v1, $v2) : Permission{
        return new Permission(2,$v1->id & 2, $v2->id, $v1->name." и ".$v2->name, "комбинация ИЛИ");
    }

}

class Permissions{
    private static array $permissions = [];


    public static function getPermissions(){
        if (count(self::$permissions) === 0) {
            self::$permissions = array(
                permissions_types::Admin => new Permission(0x01, "Admin", "Admin"),
                permissions_types::AnswerWithoutRating => new Permission(0x02, "AWR", "Answer Without Rating"),
            );
        }
        return Permissions::$permissions;
    }

    public static function getPermission($name) : Permission{
        return Permissions::getPermissions()[$name];
    }

    public static function testPemission(Permission $permission, Permission $need){
        return Permission::and($permission, $need)->getId() != 0;
    }

    public static function testIntPermission(int $permission, Permission $need){
        return $need->getId() & $permission != 0;
    }

    public static function or(array $permissions): int{
        $value = 0;
        foreach ($permissions as $permission) {
            $value |= $permission->getId();
        }
        return $value;
    }

}
