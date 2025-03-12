<?php

include_once ROOT."entities/models/category.php";
include_once ROOT."utils/db_utils.php";

class Task {
    private string $uuid;
    private string $name;
    private string $description;
    private string $attachment;
    private string $creator;
    private string $create;
    private int $value;
    private string $answer;
    private string $end_time;
    private bool $hidden;
    private string $branch;
    private int $difficulty;
    private string $category;
    private int $user_group;

    public function __construct(string $uuid, string $name, string $description, string $attachment, string $creator, string $create, int $value, string $answer, string $end_time, bool $hidden, string $branch, int $difficulty, string $category, int $user_group) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->attachment = $attachment;
        $this->creator = $creator;
        $this->value = $value;
        $this->answer = $answer;
        $this->end_time = $end_time;
        $this->hidden = $hidden;
        $this->branch = $branch;
        $this->difficulty = $difficulty;
        $this->category = $category;
        $this->create = $create;
        $this->user_group = $user_group;
    }

    public function getUuid(): string {
        return $this->uuid;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getAttachment(): string {
        return $this->attachment;
    }

    public function getCreator(): string {
        return $this->creator;
    }

    public function getValue(): int {
        return $this->value;
    }

    public function getAnswer(): string {
        return $this->answer;
    }

    public function getEndTime(): string {
        return $this->end_time;
    }

    public function isHidden(): bool {
        return $this->hidden;
    }

    public function getBranch(): string {
        return $this->branch;
    }

    public function getDifficulty(): int {
        return $this->difficulty;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function getCategoryObj(): Categoty{
        return Categoty::fromData(DBUtils::getInstance()->getCategoryByUUID($this->category));
    }

    public function getCreate(): string{
        return $this->create;
    }
    public function getUserGroup(): int{
        return $this->user_group;
    }

    public static function fromData(array $data): self {

        /*if (!isset($data['uuid']) or !isset($data['name']) or !isset($data['description']) or !isset($data['attachment']) or !isset($data['creator']) or !isset($data['create']) or !isset($data['value']) or !isset($data['answer']) or !isset($data['end_time']) or !isset($data['hidden']) or !isset($data['branch']) or !isset($data['difficulty']) or !isset($data['category'])) {
            return null;
        }*/

        return new Task(
            $data['uuid'],
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['attachment'] ?? '',
            $data['creator'] ?? '',
            $data['create'] ?? '',
            (int)$data['value'] ?? 0,
            $data['answer'] ?? '',
            $data['end_time'] ?? '',
            (bool)$data['hidden'] ?? true,
            $data['branch'],
            (int)$data['difficulty'] ?? 0,
            $data['category'],
            $data['user_group']
        );
    }




}