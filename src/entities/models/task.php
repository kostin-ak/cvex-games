<?php

include_once __DIR__."/../../configs/config.php";
include_once ROOT."/entities/models/db_model.php";

include_once ROOT."entities/models/category.php";
include_once ROOT."utils/db_utils.php";

class Task extends DBModel{
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
    private string $task_text;
    private int $user_group;
    private int $time_limit;

    public function __construct(string $uuid, string $name, string $description, string $attachment, string $creator, string $create, int $value, string $answer, string $end_time, bool $hidden, string $branch, int $difficulty, string $category, string $task_text, int $user_group, int $time_limit) {
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
        $this->task_text = $task_text;
        $this->user_group = $user_group;
        $this->time_limit = $time_limit;
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

    public function getValueWithMul(float $k=1): int {
        return $this->value*$k;
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
        return Categoty::fromData(DBUtils::getInstance()->categories()->getByUUID($this->category));
    }

    public function getCreate(): string{
        return $this->create;
    }
    public function getUserGroup(): int{
        return $this->user_group;
    }
    public function getTaskText(): string{
        return $this->task_text;
    }

    public function getTimeLimit(): int {
        return $this->time_limit;
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
            $data['task_text'] ?? "",
            $data['user_group'],
            $data['time_limit'] ?? 0
        );
    }




}