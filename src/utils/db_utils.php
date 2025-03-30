<?php

include_once ROOT."/utils/database.php";
include_once ROOT."/entities/models/user.php";
include_once ROOT."/entities/models/task.php";
include_once ROOT."/entities/models/result.php";

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

    public function getUserByLoginOrEmail($login) {
        $stmt = $this->connect->prepare("SELECT * FROM users WHERE username = :login OR mail = :login");
        $stmt->execute([':login' => $login]);
        return $stmt->fetch();
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

    public function getTasks($page, $limit, $category = null, $difficulty = null, $user = false, $admin = false, $with_passed = false) {
        $offset = ($page - 1) * $limit;

        $query = $this->buildTasksQuery($user, $admin);

        if ($category) {
            $query .= " AND t.category = :category";
        }
        if ($difficulty) {
            $query .= " AND t.difficulty = :difficulty";
        }

        if (!$user) {
            $query .= " ORDER BY r.popularity DESC";
        } else {
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
        return $tasks ? array_map([Task::class, 'fromData'], $tasks) : [];
    }

    public function getTotalPages($limit, $category = null, $difficulty = null, $user = false, $admin = false) {
        $query = "SELECT COUNT(t.*) FROM (" . $this->buildTasksQuery($user, $admin) . ") t";

        if ($category) {
            $query .= " AND t.category = :category";
        }
        if ($difficulty) {
            $query .= " AND t.difficulty = :difficulty";
        }

        $stmt = $this->connect->prepare($query);

        if ($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        if ($difficulty) {
            $stmt->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
        }

        $stmt->execute();
        return ceil($stmt->fetchColumn() / $limit);
    }


    public function getTaskByUUID($uuid) {
        $stmt = $this->connect->prepare("SELECT * FROM tasks WHERE uuid = :uuid");
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        return $task ? Task::fromData($task) : null;
    }

    public function getPassedTaskCount($task_uuid){
        return $this->connect->query("SELECT COUNT(*) FROM results WHERE task = '".$task_uuid."' AND state = 1")->fetch();
    }

    public function getTaskFirstBlood($task_uuid){
        $query = "SELECT 
                u.* AS us,  -- замените на нужные поля из таблицы users
                r.date AS first_blood_date
            FROM 
                results r
            JOIN 
                users u ON r.user = u.uuid
            WHERE 
                r.task = '".$task_uuid."'  -- замените на нужный UUID задания
            ORDER BY 
                r.date
            LIMIT 1";
        return $this->connect->query($query)->fetch();
    }

    public function getCountPassedTasksByCategories($user_uuid) {
        // Получаем все категории
        $stmt = $this->connect->prepare("SELECT uuid, name FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Подготавливаем массив с категориями и инициализируем их значениями 0
        $countByCategories = [];
        foreach ($categories as $category) {
            $countByCategories[$category['name']] = 0; // Инициализируем значением 0
        }

        // Подготовка SQL-запроса для подсчета выполненных заданий
        $stmt = $this->connect->prepare("
        SELECT c.name AS category_name, COUNT(r.task) AS task_count
        FROM results r
        JOIN tasks t ON r.task = t.uuid
        JOIN categories c ON t.category = c.uuid
        WHERE r.state = 1 AND r.user = :user_uuid
        GROUP BY c.name
    ");

        // Привязка параметра
        $stmt->bindParam(':user_uuid', $user_uuid, PDO::PARAM_STR);

        // Выполнение запроса
        $stmt->execute();

        // Получение результатов
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Обновляем массив с количеством выполненных заданий
        foreach ($results as $row) {
            $countByCategories[$row['category_name']] = (int)$row['task_count'];
        }

        // Сортируем массив по ключам (названиям категорий) в алфавитном порядке
        ksort($countByCategories);

        return $countByCategories;
    }

    public function getPercentageOfPassedTasksByCategories($user_uuid) {
        // Получаем количество выполненных заданий по категориям
        $countByCategories = $this->getCountPassedTasksByCategories($user_uuid);

        // Считаем общее количество выполненных заданий
        $totalCount = array_sum($countByCategories);

        // Если общее количество равно 0, возвращаем массив с нулями
        if ($totalCount === 0) {
            return array_fill_keys(array_keys($countByCategories), 0);
        }

        // Подсчитываем процентное содержание для каждой категории
        $percentageByCategories = [];
        foreach ($countByCategories as $category => $count) {
            $percentageByCategories[$category] = ($count / $totalCount) * 100;
        }

        return $percentageByCategories;
    }
    public function getCompletedTasksByUser ($user_uuid) {
        // Подготовка SQL-запроса для получения выполненных заданий
        $stmt = $this->connect->prepare("
        SELECT r.*, t.*, c.uuid AS category_uuid
        FROM results r
        JOIN tasks t ON r.task = t.uuid
        JOIN categories c ON t.category = c.uuid
        WHERE r.state = 1 AND r.user = :user_uuid
    ");

        // Привязка параметра
        $stmt->bindParam(':user_uuid', $user_uuid, PDO::PARAM_STR);

        // Выполнение запроса
        $stmt->execute();

        // Получение результатов
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Формирование массива с выполненными заданиями
        $completedTasks = [];
        foreach ($results as $row) {
            // Извлекаем поля результата
            $resultFields = array_intersect_key($row, array_flip(['uuid', 'user', 'task', 'state', 'date']));

            // Извлекаем поля задания
            $taskFields = array_diff_key($row, $resultFields);

            // Убираем поле category из taskFields и добавляем только uuid категории
            $categoryUuid = $row['category_uuid'];
            unset($taskFields['category_uuid']);

            // Добавляем uuid в массив task
            $taskFields['uuid'] = $row['task']; // или $row['task_uuid'], если это поле есть

            // Формируем массив с результатом, где task будет подмассивом
            $completedTasks[] = array_merge($resultFields, ['task' => $taskFields]);
        }

        return $completedTasks;
    }

    public function getCompletedTasksByUserASObj($user_uuid){
        $results = $this->getCompletedTasksByUser($user_uuid);
        $resultat = [];
        foreach ($results as $result) {
            array_push($resultat, Result::fromData($result));
        }
        return $resultat;
    }

    private function buildTasksQuery($user, $admin) {
        $query = "SELECT t.* FROM tasks t";

        if ($user) {
            $user_uuid = $_SESSION['user_uuid'];
            if (!$admin) {
                $query .= " LEFT JOIN results r ON t.uuid = r.task AND r.user = '".$user_uuid."'";
                $query .= " WHERE r.uuid IS NULL";
            }

            $user_group = $_SESSION['user']['group'];
            if ($user_group != 0) {
                $query .= " AND t.user_group LIKE '%{$user_group}%'";
            }

            if (!$admin) {
                $query .= " AND t.hidden = false";
            }
        } else {
            $query = "SELECT t.*, r.popularity FROM tasks t ".
                "JOIN (SELECT task, COUNT(*) AS popularity FROM results GROUP BY task) r ".
                "ON t.uuid = r.task JOIN categories c ON t.category = c.uuid ".
                "WHERE c.is_public = true AND t.hidden = false";
        }

        return $query;
    }
}