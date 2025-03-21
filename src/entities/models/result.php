<?php

include_once __DIR__."/../../configs/config.php";
include_once ROOT."/entities/models/db_model.php";
include_once ROOT."/entities/models/task.php";

include_once ROOT."entities/models/category.php";
include_once ROOT."utils/db_utils.php";


class Result extends DBModel{
    public string $user;
    public Task $task;
    public int $state;
    public string $date;

    function __construct(string $uuid, string $user, Task $task, int $state, string $date){
        $this->uuid = $uuid;
        $this->user = $user;
        $this->task = $task;
        $this->state = $state;
        $this->date = $date;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public static function fromData(array $data): self {
        return new self(
            $data['uuid'],
            $data['user'],
            Task::fromData($data['task']),
            $data['state'],
            $data['date']
        );
    }
}