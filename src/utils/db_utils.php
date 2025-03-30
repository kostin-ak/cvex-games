<?php

include_once ROOT."/utils/database.php";
include_once ROOT."/entities/models/user.php";
include_once ROOT."/entities/models/task.php";
include_once ROOT."/entities/models/result.php";

// Базовый класс для работы с таблицами
abstract class BaseTable {
    protected PDO $connect;

    public function __construct() {
        $this->connect = Connection::get()->connect();
    }

    public function executeQuery(string $query, array $params = []): PDOStatement {
        $stmt = $this->connect->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}

class Table extends BaseTable {


}

class CategoriesTable extends BaseTable {
    public function getList(): array {
        $query = "SELECT * FROM categories ORDER BY in_dev ASC, name ASC";
        return $this->executeQuery($query)->fetchAll();
    }

    public function getByUUID(string $uuid): ?array {
        $query = "SELECT * FROM categories WHERE uuid = :uuid";
        $result = $this->executeQuery($query, [':uuid' => $uuid])->fetch();
        return $result ?: null;
    }

    public function getPublicCategories(): array {
        $query = "SELECT * FROM categories WHERE is_public = true";
        return $this->executeQuery($query)->fetchAll();
    }
}

class UsersTable extends BaseTable {
    public function getByLoginOrEmail(string $login): ?array {
        $query = "SELECT * FROM users WHERE username = :login OR mail = :login";
        $result = $this->executeQuery($query, [':login' => $login])->fetch();
        return $result ?: null;
    }

    public function getRankByUUID(string $uuid): int {
        $query = "SELECT rank FROM (
                    SELECT dense_rank() OVER w AS rank, *
                    FROM users
                    WINDOW w AS (ORDER BY score DESC)
                ) AS ranked_users
                WHERE uuid = :uuid";
        $result = $this->executeQuery($query, [':uuid' => $uuid])->fetch();
        return $result ? (int)$result['rank'] : 0;
    }
}

class TasksTable extends BaseTable {
    public function getByUUID(string $uuid): ?Task {
        $query = "SELECT * FROM tasks WHERE uuid = :uuid";
        $result = $this->executeQuery($query, [':uuid' => $uuid])->fetch(PDO::FETCH_ASSOC);
        return $result ? Task::fromData($result) : null;
    }

    public function getPassedCount(string $task_uuid): int {
        $query = "SELECT COUNT(*) FROM results WHERE task = :task_uuid AND state = 1";
        return (int)$this->executeQuery($query, [':task_uuid' => $task_uuid])->fetchColumn();
    }

    public function getFirstBlood(string $task_uuid): ?array {
        $query = "SELECT 
                    u.*,
                    r.date AS first_blood_date
                FROM results r
                JOIN users u ON r.user = u.uuid
                WHERE r.task = :task_uuid
                ORDER BY r.date
                LIMIT 1";
        return $this->executeQuery($query, [':task_uuid' => $task_uuid])->fetch();
    }

    public function getList(
        int $page,
        int $limit,
        ?string $category = null,
        ?int $difficulty = null,
        ?bool $user = false,
        ?bool $admin = false
    ): array {
        $offset = ($page - 1) * $limit;
        $query = $this->buildQuery($user, $admin);
        $params = [];
        $where = strpos($query, 'WHERE') !== false ? 'AND' : 'WHERE';

        if ($category) {
            $query .= " {$where} t.category = :category";
            $params[':category'] = $category;
            $where = 'AND';
        }

        if ($difficulty) {
            $query .= " {$where} t.difficulty = :difficulty";
            $params[':difficulty'] = $difficulty;
        }

        $query .= $user ? " ORDER BY t.create DESC" : " ORDER BY r.popularity DESC";
        $query .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->connect->prepare($query);
        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tasks ? array_map([Task::class, 'fromData'], $tasks) : [];
    }

    public function getTotalPages(
        int $limit,
        ?string $category = null,
        ?int $difficulty = null,
        bool $user = false,
        bool $admin = false
    ): int {
        // Получаем базовый запрос без сортировки и лимитов
        $query = $this->buildQuery($user, $admin);

        // Преобразуем SELECT в SELECT COUNT(*)
        $query = preg_replace('/SELECT .*? FROM/', 'SELECT COUNT(*) FROM', $query);

        // Добавляем дополнительные условия
        $params = [];
        $whereExists = strpos($query, 'WHERE') !== false;

        $additionalConditions = [];
        if ($category) {
            $additionalConditions[] = "t.category = :category";
            $params[':category'] = $category;
        }
        if ($difficulty) {
            $additionalConditions[] = "t.difficulty = :difficulty";
            $params[':difficulty'] = $difficulty;
        }

        if (!empty($additionalConditions)) {
            $query .= $whereExists ? " AND " : " WHERE ";
            $query .= implode(" AND ", $additionalConditions);
        }

        try {
            $stmt = $this->connect->prepare($query);
            foreach ($params as $key => $value) {
                $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $paramType);
            }
            $stmt->execute();
            $totalCount = (int)$stmt->fetchColumn();
            return (int)ceil($totalCount / $limit);
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            error_log("Query: " . $query);
            return 0;
        }
    }

    private function buildQuery(bool $user, bool $admin): string {
        $baseQuery = "SELECT t.*";
        $joins = [];
        $conditions = [];

        if ($user) {
            $user_uuid = $_SESSION['user_uuid'] ?? '';
            if (!$admin && $user_uuid) {
                $joins[] = "LEFT JOIN results r ON t.uuid = r.task AND r.user = '{$user_uuid}'";
                $conditions[] = "r.uuid IS NULL";
            }

            $user_group = $_SESSION['user']['group'] ?? 0;
            if ($user_group != 0) {
                $conditions[] = "t.user_group LIKE '%{$user_group}%'";
            }

            if (!$admin) {
                $conditions[] = "t.hidden = false";
            }
        } else {
            $baseQuery .= ", r.popularity";
            $joins = [
                "JOIN (SELECT task, COUNT(*) AS popularity FROM results GROUP BY task) r ON t.uuid = r.task",
                "JOIN categories c ON t.category = c.uuid"
            ];
            $conditions = [
                "c.is_public = true",
                "t.hidden = false"
            ];
        }

        $query = $baseQuery . " FROM tasks t";

        if (!empty($joins)) {
            $query .= " " . implode(" ", $joins);
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        return $query;
    }
}

class ResultsTable extends BaseTable {
    public function getCompletedByUser(string $user_uuid): array {
        $query = "SELECT r.*, t.*, c.uuid AS category_uuid
                FROM results r
                JOIN tasks t ON r.task = t.uuid
                JOIN categories c ON t.category = c.uuid
                WHERE r.state = 1 AND r.user = :user_uuid";

        $results = $this->executeQuery($query, [':user_uuid' => $user_uuid])->fetchAll(PDO::FETCH_ASSOC);

        $completedTasks = [];
        foreach ($results as $row) {
            $resultFields = array_intersect_key($row, array_flip(['uuid', 'user', 'task', 'state', 'date']));
            $taskFields = array_diff_key($row, $resultFields);
            unset($taskFields['category_uuid']);
            $taskFields['uuid'] = $row['task'];
            $completedTasks[] = array_merge($resultFields, ['task' => $taskFields]);
        }

        return $completedTasks;
    }

    public function getCompletedByUserAsObjects(string $user_uuid): array {
        $results = $this->getCompletedByUser($user_uuid);
        return array_map([Result::class, 'fromData'], $results);
    }

    public function getCountPassedByCategories(string $user_uuid): array {
        $categories = (new CategoriesTable())->getList();
        $countByCategories = array_column($categories, 'name');
        $countByCategories = array_fill_keys($countByCategories, 0);

        $query = "SELECT c.name AS category_name, COUNT(r.task) AS task_count
                FROM results r
                JOIN tasks t ON r.task = t.uuid
                JOIN categories c ON t.category = c.uuid
                WHERE r.state = 1 AND r.user = :user_uuid
                GROUP BY c.name";

        $results = $this->executeQuery($query, [':user_uuid' => $user_uuid])->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $countByCategories[$row['category_name']] = (int)$row['task_count'];
        }

        ksort($countByCategories);
        return $countByCategories;
    }

    public function getPercentagePassedByCategories(string $user_uuid): array {
        $countByCategories = $this->getCountPassedByCategories($user_uuid);
        $totalCount = array_sum($countByCategories);

        if ($totalCount === 0) {
            return array_fill_keys(array_keys($countByCategories), 0);
        }

        $percentageByCategories = [];
        foreach ($countByCategories as $category => $count) {
            $percentageByCategories[$category] = ($count / $totalCount) * 100;
        }

        return $percentageByCategories;
    }
}

// Фасад для доступа ко всем таблицам
class DBUtils {
    private static $instance;
    private CategoriesTable $categories;
    private UsersTable $users;
    private TasksTable $tasks;
    private ResultsTable $results;

    private BaseTable $table;

    private function __construct() {
        $this->categories = new CategoriesTable();
        $this->users = new UsersTable();
        $this->tasks = new TasksTable();
        $this->results = new ResultsTable();
        $this->table = new Table();
    }

    public static function getInstance(): self {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function categories(): CategoriesTable {
        return $this->categories;
    }

    public function users(): UsersTable {
        return $this->users;
    }

    public function tasks(): TasksTable {
        return $this->tasks;
    }

    public function results(): ResultsTable {
        return $this->results;
    }

    public function table(): BaseTable {
        return $this->table;
    }

}