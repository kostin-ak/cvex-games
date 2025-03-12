<?php

include_once ROOT."/utils/database.php";
include_once ROOT."/entities/models/user.php";
include_once ROOT."/entities/models/task.php";

class DBUtils{
    private PDO $connect;
    private static $instance;
    private function __construct(){
        $this->connect = Connection::get()->connect();
    }

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new DBUtils();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->connect;
    }

    public function getCategoriesList(){
        return $this->connect->query("SELECT * FROM categories ORDER BY in_dev ASC, name ASC")->fetchAll();
    }

    public function getCategoryByUUID($uuid){
        return $this->connect->query("SELECT * FROM categories WHERE uuid = '{$uuid}'")->fetch();
    }

    public function getUserByLoginOrEmail($login){
        $user = $this->connect->query("SELECT * FROM users WHERE username = '{$login}' OR mail = '{$login}'")->fetch();
        return $user;
    }

    public function getRankUserByUUID($uuid){
        $rank = $this->connect->query("SELECT rank FROM (
                                                SELECT dense_rank() OVER w AS rank, *
                                                    FROM users
                                                    WINDOW w AS (ORDER BY score DESC)
                                                ) AS ranked_users
                                                WHERE uuid = '{$uuid}';
                                            ")->fetch();
        if ($rank){
            return $rank['rank'];
        }
        return 0;
    }

    public function getTasks($page, $limit, $category = null, $difficulty = null, $user = false, $admin = false) {
        $offset = ($page - 1) * $limit;

        if ($user) {

            $user_group = $_SESSION['user']['group'];
            $user_uuid = $_SESSION['user_uuid'];

            $uuid_need = !$admin?"LEFT JOIN results r ON t.uuid = r.task AND r.user = '".$user_uuid."'":"";
            $uuid_need2 = !$admin?" AND r.uuid IS NULL":"";

            $query = "SELECT t.* FROM tasks t ".$uuid_need." WHERE 1=1".$uuid_need2;

            if ($user_group != 0){
                $query .= " AND user_group LIKE '%{$user_group}%'";
            }


            if (!$admin) {
                $query .= " AND t.hidden = false";
            }
        }
        else{
            //$query = "SELECT t.* FROM tasks t JOIN categories c ON t.category = c.uuid WHERE c.is_public = true AND t.hidden = false";
            $query = "SELECT t.*, r.popularity FROM tasks t JOIN (SELECT task, COUNT(*) AS popularity FROM results GROUP BY task) r ON t.uuid = r.task JOIN categories c ON t.category = c.uuid WHERE c.is_public = true AND t.hidden = false";

        }

        if ($category) {
            $query .= " AND t.category = :category";
        }
        if ($difficulty) {
            $query .= " AND t.difficulty = :difficulty";
        }


        if (!$user){
            $query .= " ORDER BY r.popularity DESC";
        }else{
            $query .= " ORDER BY t.create DESC";
        }


        $query .= " LIMIT :limit OFFSET :offset";


        $stmt = $this->connect->prepare($query);
        if ($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        if ($difficulty) {
            $stmt->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Если нет задач, возвращаем пустой массив
        return $tasks ? array_map([Task::class, 'fromData'], $tasks) : [];
    }

    public function getTotalPages($limit, $category = null, $difficulty = null, $user = false, $admin = false) {
        if ($user) {
            $user_group = $_SESSION['user']['group'];
            $user_uuid = $_SESSION['user_uuid'];

            $uuid_need = !$admin?"LEFT JOIN results r ON t.uuid = r.task AND r.user = '".$user_uuid."'":"";
            $uuid_need2 = !$admin?" AND r.uuid IS NULL":"";

            $query = "SELECT COUNT(t.*) FROM tasks t ".$uuid_need." WHERE 1=1".$uuid_need2;
            if ($user_group != 0){
                $query .= " AND user_group LIKE '%{$user_group}%'";
            }

            if (!$admin) {
                $query .= " AND t.hidden = false";
            }
        }
        else{
            //$query = "SELECT COUNT(t.*) FROM tasks t JOIN categories c ON t.category = c.uuid WHERE c.is_public = true AND t.hidden = false";
            $query = "SELECT COUNT(t.*) FROM tasks t JOIN (SELECT task FROM results GROUP BY task) r ON t.uuid = r.task JOIN categories c ON t.category = c.uuid WHERE c.is_public = true AND t.hidden = false";
        }

        if ($category) {
            $query .= " AND t.category = :category";
        }
        if ($difficulty) {
            $query .= " AND t.difficulty = :difficulty";
        }

        $totalStmt = $this->connect->prepare($query);
        if ($category) {
            $totalStmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        if ($difficulty) {
            $totalStmt->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
        }
        $totalStmt->execute();
        return ceil($totalStmt->fetchColumn()/$limit);
    }


    public function getTaskByUUID($uuid) {
        $stmt = $this->connect->prepare("SELECT * FROM tasks WHERE uuid = :uuid");
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        return $task ? Task::fromData($task) : null;
    }
}