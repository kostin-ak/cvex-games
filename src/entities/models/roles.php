<?php

    include_once __DIR__."/../../configs/config.php";
    include_once ROOT."/entities/models/permissions.php";
    include_once ROOT."/configs/config.php";


class Role{
        private int $id;
        private string $name;
        private string $description;
        private int $permissions;

        private string $class;

        private static array $RULES = [];

        public function __construct(int $id, string $name, string $description, int $permissions, string $class){
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->permissions = $permissions;
            $this->class = $class;
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
                Role::$RULES = Config::getRoles();
            }

            foreach(Role::$RULES as $rule){
                if($rule->getId() == $id){
                    return $rule;
                    break;
                }
            }
            return Role::$RULES[0];

        }

        public function getClass(): string
        {
            return $this->class;
        }

    }

    function getRootPermissions(): int{
        return Permissions::or(Permissions::getPermissions());
    }
    function getTesterPermissions(): int{
        return Permissions::or(Permissions::getPermissions(permissions_types::AnswerWithoutRating));
    }


?>