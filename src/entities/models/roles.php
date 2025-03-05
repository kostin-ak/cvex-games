<?php

    include_once "entities/models/permissions.php";

    class Role{
        private int $id;
        private string $name;
        private string $description;
        private int $permissions;

        private static array $RULES = [];

        public function __construct(int $id, string $name, string $description, int $permissions){
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->permissions = $permissions;
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getName(): string{
            return $this->name;
        }

        public function getDescription(): string{
            return $this->description;
        }

        public function getPermissions(): int{
            return $this->permissions;
        }

        public static function getRuleById($id): Role{

            if (count(Role::$RULES) == 0){
                Role::$RULES = array(
                    new Role(0, "user", "Обычный пользователь", 0),
                    new Role(1, "root", "Суперпользователь", getRootPermissions()),
                    new Role(1, "tester", "Тестировщик", getTesterPermissions()),
                );
            }

            foreach(Role::$RULES as $rule){
                if($rule->getId() == $id){
                    return $rule;
                    break;
                }
            }
            return Role::$RULES[0];

        }

    }

    function getRootPermissions(): int{
        return Permissions::or(Permissions::getPermissions());
    }
    function getTesterPermissions(): int{
        return Permissions::or(Permissions::getPermissions(permissions_types::AnswerWithoutRating));
    }


?>