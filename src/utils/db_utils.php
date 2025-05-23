<?php

include_once ROOT."/utils/database.php";
include_once ROOT."/entities/models/user.php";
include_once ROOT."/entities/models/task.php";
include_once ROOT."/entities/models/result.php";

/**
 * Базовый класс для работы с таблицами базы данных
 */
abstract class BaseTable
{
    protected PDO $connect;

    public function __construct()
    {
        $this->connect = Connection::get()->connect();
    }

    /**
     * Выполняет SQL-запрос с параметрами
     */
    public function executeQuery(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->connect->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}

class Table extends BaseTable
{
    // Пустой класс-наследник для будущего расширения
}

/**
 * Класс для работы с таблицей категорий
 */
class CategoriesTable extends BaseTable
{
    public function getList(): array
    {
        $query = "SELECT * FROM categories ORDER BY in_dev ASC, name ASC";
        return $this->executeQuery($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUUID(string $uuid): ?array
    {
        $query = "SELECT * FROM categories WHERE uuid = :uuid";
        $result = $this->executeQuery($query, [':uuid' => $uuid])->fetch();
        return $result ?: null;
    }

    public function getPublicCategories(): array
    {
        $query = "SELECT * FROM categories WHERE is_public = true";
        return $this->executeQuery($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Добавляет новую категорию
     */
    public function addCategory(array $categoryData): bool
    {
        if (empty($categoryData['name'])) {
            throw new InvalidArgumentException("Category name is required");
        }

        $fields = ['name', 'description', 'is_public', 'in_dev', 'image'];
        $filteredData = array_intersect_key($categoryData, array_flip($fields));

        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));

        $query = "INSERT INTO categories ({$columns}) VALUES ({$placeholders})";

        try {
            $stmt = $this->connect->prepare($query);

            foreach ($filteredData as $key => $value) {
                $paramType = is_bool($value) ? PDO::PARAM_BOOL : (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                $stmt->bindValue(':' . $key, $value, $paramType);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding category: " . $e->getMessage());
            throw new RuntimeException("Failed to add category");
        }
    }

    /**
     * Обновляет существующую категорию по UUID
     */
    public function updateCategory(string $uuid, array $categoryData): bool
    {
        if (empty($uuid)) {
            throw new InvalidArgumentException("Category UUID is required");
        }

        // Проверяем существование категории
        $existing = $this->getByUUID($uuid);
        if (!$existing) {
            throw new RuntimeException("Category not found");
        }

        $allowedFields = ['name', 'description', 'is_public', 'in_dev', 'image'];
        $filteredData = array_intersect_key($categoryData, array_flip($allowedFields));

        if (empty($filteredData)) {
            return false; // Нет данных для обновления
        }

        $setParts = [];
        foreach ($filteredData as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }

        $query = "UPDATE categories SET " . implode(', ', $setParts) . " WHERE uuid = :uuid";
        $filteredData['uuid'] = $uuid;

        try {
            $stmt = $this->connect->prepare($query);

            foreach ($filteredData as $key => $value) {
                $paramType = is_bool($value) ? PDO::PARAM_BOOL : (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                $stmt->bindValue(':' . $key, $value, $paramType);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating category: " . $e->getMessage());
            throw new RuntimeException("Failed to update category");
        }
    }

    /**
     * Удаляет категорию по UUID
     */
    public function deleteCategory(string $uuid): bool
    {
        if (empty($uuid)) {
            throw new InvalidArgumentException("Category UUID is required");
        }

        // Проверяем, что категория не используется в задачах
        $queryCheck = "SELECT COUNT(*) FROM tasks WHERE category = :uuid";
        $count = $this->executeQuery($queryCheck, [':uuid' => $uuid])->fetchColumn();

        if ($count > 0) {
            throw new RuntimeException("Cannot delete category - it's being used by tasks");
        }

        $query = "DELETE FROM categories WHERE uuid = :uuid";

        try {
            return $this->executeQuery($query, [':uuid' => $uuid])->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting category: " . $e->getMessage());
            throw new RuntimeException("Failed to delete category");
        }
    }
}

/**
 * Класс для работы с таблицей пользователей
 */
class UsersTable extends BaseTable
{
    public function getByLoginOrEmail(string $login): ?array
    {
        $query = "SELECT * FROM users WHERE username = :login OR mail = :login";
        $result = $this->executeQuery($query, [':login' => $login])->fetch();
        return $result ?: null;
    }

    public function getRankByUUID(string $uuid): int
    {
        $query = "SELECT rank FROM (
                    SELECT dense_rank() OVER w AS rank, *
                    FROM users
                    WINDOW w AS (ORDER BY score DESC)
                ) AS ranked_users
                WHERE uuid = :uuid";
        $result = $this->executeQuery($query, [':uuid' => $uuid])->fetch();
        return $result ? (int)$result['rank'] : 0;
    }

    /**
     * Получает рейтинг пользователей с фильтрацией по группе
     */
    public function getRatingByGroup(
        ?int $group = null,
        int $limit = 100,
        int $offset = 0
    ): array
    {
        $params = [];
        $where = $group !== null ? 'WHERE "group" = :group' : 'WHERE "role" = 0 ';

        if ($group !== null) {
            $params[':group'] = $group;
        }

        $query = "SELECT 
                    u.*,
                    dense_rank() OVER (ORDER BY u.score DESC) AS rank
                  FROM users u
                  {$where}
                  ORDER BY rank ASC, u.username ASC
                  LIMIT :limit OFFSET :offset";

        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->connect->prepare($query);

        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получает общее количество пользователей в рейтинге
     */
    public function getRatingCountByGroup(?int $group = null): int
    {
        $params = [];
        $where = $group !== null ? 'WHERE "group" = :group' : '';

        if ($group !== null) {
            $params[':group'] = $group;
        }

        $query = "SELECT COUNT(*) FROM users {$where}";
        return (int)$this->executeQuery($query, $params)->fetchColumn();
    }

    public function usernameExists(string $username): bool
    {
        $query = "SELECT COUNT(*) FROM users WHERE username = :username";
        $count = $this->executeQuery($query, [':username' => $username])->fetchColumn();
        return (bool)$count;
    }

    public function emailExists(string $email): bool
    {
        $query = "SELECT COUNT(*) FROM users WHERE mail = :email";
        $count = $this->executeQuery($query, [':email' => $email])->fetchColumn();
        return (bool)$count;
    }

    public function exists(string $value, string $type = 'both'): bool
    {
        $conditions = [];
        $params = [];

        if ($type === 'both' || $type === 'username') {
            $conditions[] = "username = :username";
            $params[':username'] = $value;
        }

        if ($type === 'both' || $type === 'email') {
            $conditions[] = "mail = :email";
            $params[':email'] = $value;
        }

        $where = implode(' OR ', $conditions);
        $query = "SELECT COUNT(*) FROM users WHERE {$where}";

        $count = $this->executeQuery($query, $params)->fetchColumn();
        return (bool)$count;
    }

    public function createUser(array $userData): bool
    {
        if (empty($userData['username']) || empty($userData['mail'])) {
            throw new InvalidArgumentException("Required user fields are missing");
        }

        // Проверка уникальности username и email
        if ($this->usernameExists($userData['username'])) {
            throw new RuntimeException("Username already exists");
        }

        if ($this->emailExists($userData['mail'])) {
            throw new RuntimeException("Email already exists");
        }

        $query = "INSERT INTO users (
            username, 
            mail, 
            name, 
            sname,
            password, 
            role,
            score, 
            \"group\", 
            registered
          ) VALUES ( 
            :username, 
            :mail, 
            :name, 
            :sname,
            :password, 
            :role,
            :score, 
            :group, 
            :registered
          )";

        $defaults = [
            ':score' => 0,
            ':role' => 0,
            ':registered' => date('Y-m-d H:i:s'),
        ];

        // Объединяем переданные данные с значениями по умолчанию
        $params = array_merge($defaults, [
            ':username' => $userData['username'],
            ':name' => $userData['name'] ?? null,
            ':sname' => $userData['sname'] ?? null,
            ':mail' => $userData['mail'],
            ':group' => $userData['group'] ?? 0,
            ':password' => $userData['password'] ?? null
        ]);

        try {
            $stmt = $this->connect->prepare($query);

            // Привязываем параметры с правильными типами
            foreach ($params as $key => $value) {
                if (is_bool($value)) {
                    $paramType = PDO::PARAM_BOOL;
                } elseif (is_int($value)) {
                    $paramType = PDO::PARAM_INT;
                } elseif (is_null($value)) {
                    $paramType = PDO::PARAM_NULL;
                } else {
                    $paramType = PDO::PARAM_STR;
                }
                $stmt->bindValue($key, $value, $paramType);
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Поиск пользователей по имени, фамилии, почте или нику
     *
     * @param string|null $search Строка для поиска (может быть null)
     * @param int $limit Ограничение количества результатов (по умолчанию 50)
     * @return User[] Массив объектов User
     */
    public function searchUsers(?string $search = null, int $limit = 10): array
    {
        $query = "SELECT * FROM users";
        $params = [];

        // Добавляем условие поиска, если задана поисковая строка
        if ($search !== null && $search !== '') {
            $query .= " WHERE (username ILIKE :search OR 
                             name ILIKE :search OR 
                             sname ILIKE :search OR 
                             mail ILIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        // Сортировка: сначала админы (role > 0), затем по username
        $query .= " ORDER BY role > 0 DESC, username ASC";

        // Ограничение количества результатов
        $query .= " LIMIT :limit";
        $params[':limit'] = $limit;

        $stmt = $this->connect->prepare($query);

        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $usersData;
    }

    public function getUserByUuid(string $uuid): ?array
    {
        $query = "SELECT * FROM users WHERE uuid = :uuid";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Обновляет данные пользователя по UUID
     *
     * @param string $uuid UUID пользователя
     * @param array $userData Ассоциативный массив с данными для обновления
     * @return bool Возвращает true при успешном обновлении
     * @throws InvalidArgumentException|RuntimeException
     */
    public function updateUser(string $uuid, array $userData): bool
    {
        if (empty($uuid)) {
            throw new InvalidArgumentException("User UUID is required");
        }

        // Проверяем существование пользователя
        $existingUser = $this->getUserByUuid($uuid);
        if (!$existingUser) {
            throw new RuntimeException("User not found");
        }

        // Проверяем уникальность нового username, если он указан
        if (isset($userData['username']) && $userData['username'] !== $existingUser['username']) {
            if ($this->usernameExists($userData['username'])) {
                throw new RuntimeException("Username already exists");
            }
        }

        // Проверяем уникальность нового email, если он указан
        if (isset($userData['mail']) && $userData['mail'] !== $existingUser['mail']) {
            if ($this->emailExists($userData['mail'])) {
                throw new RuntimeException("Email already exists");
            }
        }

        // Разрешенные поля для обновления
        $allowedFields = [
            'username', 'mail', 'name', 'sname',
            'password', 'role', 'score', 'group'
        ];
        $filteredData = array_intersect_key($userData, array_flip($allowedFields));

        if (empty($filteredData)) {
            return false; // Нет данных для обновления
        }

        // Формируем части SET запроса, экранируя зарезервированные слова
        $setParts = [];
        foreach ($filteredData as $key => $value) {
            $column = $key === 'group' ? '"group"' : $key;
            $setParts[] = "{$column} = :{$key}";
        }

        $query = "UPDATE users SET " . implode(', ', $setParts) . " WHERE uuid = :uuid";
        $filteredData['uuid'] = $uuid;

        try {
            $stmt = $this->connect->prepare($query);

            // Привязываем параметры с правильными типами
            foreach ($filteredData as $key => $value) {
                if (is_bool($value)) {
                    $paramType = PDO::PARAM_BOOL;
                } elseif (is_int($value)) {
                    $paramType = PDO::PARAM_INT;
                } elseif (is_null($value)) {
                    $paramType = PDO::PARAM_NULL;
                } else {
                    $paramType = PDO::PARAM_STR;
                }
                $stmt->bindValue(':' . $key, $value, $paramType);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            throw new RuntimeException("Failed to update user: " . $e->getMessage());
        }
    }

}

/**
 * Класс для работы с таблицей заданий
 */
class TasksTable extends BaseTable
{
    public function getByUUID(string $uuid, ?string $user_uuid = null): ?Task
    {
        $query = "SELECT t.*";

        // Добавляем поля для проверки статуса выполнения, если передан user_uuid
        if ($user_uuid !== null) {
            $query .= ", 
            CASE WHEN r_completed.uuid IS NOT NULL THEN true ELSE false END AS completed,
            CASE WHEN r_in_progress.uuid IS NOT NULL THEN true ELSE false END AS in_progress";
        }

        $query .= " FROM tasks t";

        // Добавляем JOIN для проверки статуса выполнения, если передан user_uuid
        if ($user_uuid !== null) {
            $query .= " 
            LEFT JOIN results r_completed ON t.uuid = r_completed.task 
                AND r_completed.user = :user_uuid 
                AND r_completed.state = 1
            LEFT JOIN results r_in_progress ON t.uuid = r_in_progress.task 
                AND r_in_progress.user = :user_uuid 
                AND r_in_progress.state = 2";
        }

        $query .= " WHERE t.uuid = :uuid";

        $params = [':uuid' => $uuid];
        if ($user_uuid !== null) {
            $params[':user_uuid'] = $user_uuid;
        }

        $result = $this->executeQuery($query, $params)->fetch(PDO::FETCH_ASSOC);

        //var_dump($result);

        if (!$result) {
            return null;
        }

        // Обработка полей completed и in_progress
        if ($user_uuid !== null) {
            $result['completed'] = (bool)($result['completed'] ?? false);
            $result['in_progress'] = (bool)($result['in_progress'] ?? false);
        } else {
            // Если user_uuid не передан, удаляем эти поля, чтобы они не попали в конструктор
            unset($result['completed'], $result['in_progress']);
        }

        return Task::fromData($result);
    }

    public function getPassedCount(string $task_uuid): int
    {
        $query = "SELECT COUNT(*) FROM results WHERE task = :task_uuid AND state = 1";
        return (int)$this->executeQuery($query, [':task_uuid' => $task_uuid])->fetchColumn();
    }

    public function getFirstBlood(string $task_uuid)
    {
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
        ?bool $admin = false,
        ?string $user_uuid = null,
        ?string $search = null,
        ?bool $completed = null,  // Фильтр по выполненным заданиям
        ?bool $in_progress = null  // Новый параметр для фильтрации по заданиям "в работе"
    ): array
    {
        $offset = ($page - 1) * $limit;
        $query = $this->buildQuery($user, $admin, $user_uuid);
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
            $where = 'AND';
        }

        if ($search) {
            $query .= " {$where} (t.name ILIKE :search OR t.description ILIKE :search)";
            $params[':search'] = '%' . $search . '%';
            $where = 'AND';
        }

        // Условие для фильтрации по выполненным заданиям
        if ($completed !== null && $user_uuid !== null) {
            $query .= " {$where} EXISTS (
            SELECT 1 FROM results r 
            WHERE r.task = t.uuid 
            AND r.user = :completed_user_uuid 
            AND r.state = 1
        )";
            $params[':completed_user_uuid'] = $user_uuid;
            $where = 'AND';
        }

        // Условие для фильтрации по заданиям "в работе"
        if ($in_progress !== null && $user_uuid !== null) {
            $query .= " {$where} EXISTS (
            SELECT 1 FROM results r 
            WHERE r.task = t.uuid 
            AND r.user = :in_progress_user_uuid 
            AND r.state = 2
        )";
            $params[':in_progress_user_uuid'] = $user_uuid;
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
        bool $admin = false,
        ?string $search = null,
        ?bool $completed = null,
        ?bool $in_progress = null,  // Новый параметр для фильтрации по заданиям "в работе"
        ?string $user_uuid = null
    ): int
    {
        $query = $this->buildQuery($user, $admin, $user_uuid);
        $query = preg_replace('/SELECT .*? FROM/', 'SELECT COUNT(*) FROM', $query);

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
        if ($search) {
            $additionalConditions[] = "(t.name ILIKE :search OR t.description ILIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if ($completed !== null && $user_uuid !== null) {
            $additionalConditions[] = "EXISTS (
            SELECT 1 FROM results r 
            WHERE r.task = t.uuid 
            AND r.user = :completed_user_uuid 
            AND r.state = 1
        )";
            $params[':completed_user_uuid'] = $user_uuid;
        }
        if ($in_progress !== null && $user_uuid !== null) {
            $additionalConditions[] = "EXISTS (
            SELECT 1 FROM results r 
            WHERE r.task = t.uuid 
            AND r.user = :in_progress_user_uuid 
            AND r.state = 2
        )";
            $params[':in_progress_user_uuid'] = $user_uuid;
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
    private function buildQuery(bool $user, bool $admin, ?string $user_uuid = null): string
    {
        $baseQuery = "SELECT t.*";

        if ($user_uuid !== null) {
            $baseQuery .= ", 
            CASE WHEN r_completed.uuid IS NOT NULL THEN true ELSE false END AS completed,
            CASE WHEN r_in_progress.uuid IS NOT NULL THEN true ELSE false END AS in_progress";
        }

        $joins = [];
        $conditions = [];

        if ($user) {
            $user_uuid_session = $_SESSION['user_uuid'] ?? '';
            if (!$admin && $user_uuid_session) {
                $joins[] = "LEFT JOIN results r ON t.uuid = r.task AND r.user = '{$user_uuid_session}'";
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

        if ($user_uuid !== null) {
            $joins[] = "LEFT JOIN results r_completed ON t.uuid = r_completed.task AND r_completed.user = '{$user_uuid}' AND r_completed.state = 1";
            $joins[] = "LEFT JOIN results r_in_progress ON t.uuid = r_in_progress.task AND r_in_progress.user = '{$user_uuid}' AND r_in_progress.state = 2";
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
    /**
     * Проверяет правильность ответа на задание
     *
     * @param string $task_uuid UUID задания
     * @param string $answer Ответ пользователя
     * @return bool Возвращает true если ответ верный, false если неверный
     * @throws RuntimeException Если задание не найдено
     */
    public function checkAnswer(string $task_uuid, string $answer): bool
    {
        // Получаем правильный ответ из базы данных
        $query = "SELECT answer FROM tasks WHERE uuid = :task_uuid";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':task_uuid', $task_uuid, PDO::PARAM_STR);
        $stmt->execute();

        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            throw new RuntimeException("Task not found");
        }

        // Сравниваем ответы (регистронезависимо и с удалением лишних пробелов)
        $correctAnswer = trim(strtolower($task['answer']));
        $userAnswer = trim(strtolower($answer));

        return $correctAnswer === $userAnswer;
    }
}

/**
 * Класс для работы с таблицей результатов
 */
class ResultsTable extends BaseTable
{
    public function getCompletedByUser(string $user_uuid): array
    {
        $query = "SELECT r.*, t.*, c.uuid AS category_uuid, c.in_dev
            FROM results r
            JOIN tasks t ON r.task = t.uuid
            JOIN categories c ON t.category = c.uuid
            WHERE r.state = 1 AND r.user = :user_uuid AND c.in_dev = false
            ORDER BY r.date DESC";  // Добавлена сортировка по дате (новые сначала)

        $results = $this->executeQuery($query, [':user_uuid' => $user_uuid])->fetchAll(PDO::FETCH_ASSOC);

        $completedTasks = [];
        foreach ($results as $row) {
            if ($row['in_dev']) continue;

            $resultFields = array_intersect_key($row, array_flip(['uuid', 'user', 'task', 'state', 'date']));
            $taskFields = array_diff_key($row, $resultFields);
            unset($taskFields['category_uuid'], $taskFields['in_dev']);
            $taskFields['uuid'] = $row['task'];
            $completedTasks[] = array_merge($resultFields, ['task' => $taskFields]);
        }

        return $completedTasks;
    }

    public function getCountPassedByCategories(string $user_uuid, ?string $user_group = null): array
    {
        $query = "SELECT c.name, c.in_dev 
                FROM categories c 
                WHERE c.in_dev = false
                ORDER BY c.name";
        $categories = $this->executeQuery($query)->fetchAll(PDO::FETCH_ASSOC);

        $countByCategories = array_column($categories, 'name');
        $countByCategories = array_fill_keys($countByCategories, 0);

        $query = "SELECT c.name AS category_name, COUNT(r.task) AS task_count
                FROM results r
                JOIN tasks t ON r.task = t.uuid
                JOIN categories c ON t.category = c.uuid
                WHERE r.state = 1 AND r.user = :user_uuid AND c.in_dev = false";

        $params = [':user_uuid' => $user_uuid];

        if ($user_group !== null && $user_group != 0) {
            $query .= " AND t.user_group LIKE :user_group";
            $params[':user_group'] = "%".strval($user_group)."%";
        }

        $query .= " GROUP BY c.name";

        $results = $this->executeQuery($query, $params)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $countByCategories[$row['category_name']] = (int)$row['task_count'];
        }

        ksort($countByCategories);
        return $countByCategories;
    }

    public function getCountPassedAndTotalByCategories(
        string $user_uuid,
        ?string $user_group = null
    ): array
    {
        if ($user_group == 0) $user_group = null;

        $query = "SELECT c.name, c.in_dev 
                FROM categories c 
                WHERE c.in_dev = false
                ORDER BY c.name";
        $categories = $this->executeQuery($query)->fetchAll(PDO::FETCH_ASSOC);

        $passedQuery = "SELECT c.name AS category_name, COUNT(r.task) AS task_count
                   FROM results r
                   JOIN tasks t ON r.task = t.uuid
                   JOIN categories c ON t.category = c.uuid
                   WHERE r.state = 1 AND r.user = :user_uuid AND c.in_dev = false";

        $passedParams = [':user_uuid' => $user_uuid];

        if ($user_group !== null) {
            $passedQuery .= " AND t.user_group LIKE :user_group";
            $passedParams[':user_group'] = "%{$user_group}%";
        }

        $passedQuery .= " GROUP BY c.name";
        $passedCounts = $this->executeQuery($passedQuery, $passedParams)->fetchAll(PDO::FETCH_KEY_PAIR);

        $totalQuery = "SELECT c.name AS category_name, COUNT(t.uuid) AS total_tasks
                  FROM tasks t
                  JOIN categories c ON t.category = c.uuid
                  WHERE t.hidden = false AND c.in_dev = false";

        $totalParams = [];

        if ($user_group !== null) {
            $totalQuery .= " AND t.user_group LIKE :user_group";
            $totalParams[':user_group'] = "%{$user_group}%";
        }

        $totalQuery .= " GROUP BY c.name";
        $totalCounts = $this->executeQuery($totalQuery, $totalParams)->fetchAll(PDO::FETCH_KEY_PAIR);

        $result = [];
        foreach ($categories as $category) {
            $categoryName = $category['name'];
            $result[$categoryName] = [
                'passed' => $passedCounts[$categoryName] ?? 0,
                'total' => $totalCounts[$categoryName] ?? 0
            ];
        }

        ksort($result);
        return $result;
    }

    public function getPercentagePassedByCategories(string $user_uuid): array
    {
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


    /**
     * Получает результат по UUID задания и UUID пользователя
     *
     * @param string $task_uuid UUID задания
     * @param string $user_uuid UUID пользователя
     * @return Result|null Экземпляр Result или null если не найден
     */
    public function getByTaskAndUser(string $task_uuid, string $user_uuid): ?Result
    {
        $query = "SELECT r.*, t.* FROM results r
                 JOIN tasks t ON r.task = t.uuid
                 WHERE r.task = :task_uuid AND r.user = :user_uuid";

        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':task_uuid', $task_uuid, PDO::PARAM_STR);
        $stmt->bindValue(':user_uuid', $user_uuid, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        // Преобразуем данные задачи и объединяем с данными результата
        $taskData = array_intersect_key($data, array_flip([
            'uuid', 'name', 'description', 'attachment', 'creator', 'create',
            'value', 'answer', 'end_time', 'hidden', 'branch', 'difficulty',
            'category', 'task_text', 'user_group', 'time_limit'
        ]));


        $resultData = [
            'uuid' => $data['uuid'],
            'user' => $data['user'],
            'task' => $taskData,
            'state' => (int)$data['state'],
            'date' => $data['date']
        ];

        $result = Result::fromData($resultData);

        return $result;
    }

    /**
     * Запускает задание для пользователя, создавая запись в таблице результатов
     *
     * @param string $user_uuid UUID пользователя
     * @param string $task_uuid UUID задания
     * @return Result Созданная запись результата
     * @throws RuntimeException Если не удалось создать запись
     */
    /**
     * Запускает задание для пользователя, создавая запись в таблице результатов
     *
     * @param string $user_uuid UUID пользователя
     * @param string $task_uuid UUID задания
     * @return Result Созданная запись результата
     * @throws RuntimeException Если не удалось создать запись
     */
    public function startTask(string $user_uuid, string $task_uuid): Result
    {
        // Проверяем существование пользователя и задания
        $existingResult = $this->getByTaskAndUser($task_uuid, $user_uuid);

        if ($existingResult) {
            // Если запись уже существует, просто обновляем состояние на "в работе"
            return $this->updateTaskState($user_uuid, $task_uuid, 2);
        }

        $query = 'INSERT INTO results ("user", task, state, date) 
              VALUES (:user_uuid, :task_uuid, :state, :date)
              RETURNING *';

        $params = [
            ':user_uuid' => $user_uuid,
            ':task_uuid' => $task_uuid,
            ':state' => 2, // состояние "в работе"
            ':date' => date('Y-m-d H:i:s')
        ];

        try {
            $stmt = $this->connect->prepare($query);

            foreach ($params as $key => $value) {
                $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $paramType);
            }

            $stmt->execute();
            $resultData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultData) {
                throw new RuntimeException("Failed to create task result");
            }

            // Получаем полные данные задачи
            $taskData = $this->getTaskData($task_uuid);

            // Формируем данные для создания объекта Result
            $resultFullData = [
                'uuid' => $resultData['uuid'],
                'user' => $user_uuid,
                'task' => $taskData,
                'state' => (int)$resultData['state'],
                'date' => $resultData['date']
            ];

            return Result::fromData($resultFullData);

        } catch (PDOException $e) {
            error_log("Error starting task: " . $e->getMessage());
            throw new RuntimeException("Failed to start task");
        }
    }

    /**
     * Обновляет состояние задания для пользователя
     */
    private function updateTaskState(string $user_uuid, string $task_uuid, int $state): Result
    {
        $query = 'UPDATE results 
              SET state = :state, date = :date 
              WHERE "user" = :user_uuid AND task = :task_uuid
              RETURNING *';

        $params = [
            ':user_uuid' => $user_uuid,
            ':task_uuid' => $task_uuid,
            ':state' => $state,
            ':date' => date('Y-m-d H:i:s')
        ];

        try {
            $stmt = $this->connect->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }

            $stmt->execute();
            $resultData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultData) {
                throw new RuntimeException("Failed to update task status");
            }

            $taskData = $this->getTaskData($task_uuid);

            return Result::fromData([
                'uuid' => $resultData['uuid'],
                'user' => $user_uuid,
                'task' => $taskData,
                'state' => (int)$resultData['state'],
                'date' => $resultData['date']
            ]);

        } catch (PDOException $e) {
            error_log("Error updating task state: " . $e->getMessage());
            throw new RuntimeException("Failed to update task state");
        }
    }

    /**
     * Получает данные задачи по UUID
     */
    private function getTaskData(string $task_uuid): array
    {
        $query = "SELECT * FROM tasks WHERE uuid = :task_uuid";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':task_uuid', $task_uuid, PDO::PARAM_STR);
        $stmt->execute();

        $taskData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$taskData) {
            throw new RuntimeException("Task not found");
        }

        return $taskData;
    }

    /**
     * Проверяет ответ на задание и записывает результат
     *
     * @param string $user_uuid UUID пользователя
     * @param string $task_uuid UUID задания
     * @param string $answer Ответ пользователя
     * @return bool Возвращает true если ответ верный, false если неверный
     * @throws RuntimeException Если возникла ошибка при работе с базой данных
     */
    public function verifyAndRecordTask(string $user_uuid, string $task_uuid, string $answer): bool
    {
        try {
            // Начинаем транзакцию для атомарности операций
            $this->connect->beginTransaction();

            // Получаем данные задания
            $taskQuery = "SELECT answer, value FROM tasks WHERE uuid = :task_uuid FOR UPDATE";
            $taskStmt = $this->connect->prepare($taskQuery);
            $taskStmt->bindValue(':task_uuid', $task_uuid, PDO::PARAM_STR);
            $taskStmt->execute();
            $task = $taskStmt->fetch(PDO::FETCH_ASSOC);

            if (!$task) {
                throw new RuntimeException("Task not found");
            }

            // Проверяем корректность ответа
            $correctAnswer = trim(strtolower($task['answer']));
            $userAnswer = trim(strtolower($answer));
            $isCorrect = $correctAnswer === $userAnswer;

            // Определяем состояние для записи
            $state = $isCorrect ? 1 : 3; // 1 - правильно, 3 - неправильно

            // Проверяем существующую запись результата
            $existingQuery = "SELECT uuid, state FROM results 
                         WHERE \"user\" = :user_uuid AND task = :task_uuid";
            $existingStmt = $this->connect->prepare($existingQuery);
            $existingStmt->bindValue(':user_uuid', $user_uuid, PDO::PARAM_STR);
            $existingStmt->bindValue(':task_uuid', $task_uuid, PDO::PARAM_STR);
            $existingStmt->execute();
            $existingResult = $existingStmt->fetch(PDO::FETCH_ASSOC);

            // Если запись уже существует и задание уже было выполнено - ничего не делаем
            if ($existingResult && $existingResult['state'] == 1) {
                $this->connect->rollBack();
                return true;
            }

            if ($existingResult) {
                // Обновляем существующую запись
                $updateQuery = "UPDATE results SET state = :state, date = :date
                           WHERE uuid = :uuid";
                $updateStmt = $this->connect->prepare($updateQuery);
                $updateStmt->bindValue(':state', $state, PDO::PARAM_INT);
                $updateStmt->bindValue(':date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $updateStmt->bindValue(':uuid', $existingResult['uuid'], PDO::PARAM_STR);
                $updateStmt->execute();
            } else {
                // Создаем новую запись
                $insertQuery = "INSERT INTO results (\"user\", task, state, date)
                           VALUES (:user_uuid, :task_uuid, :state, :date)";
                $insertStmt = $this->connect->prepare($insertQuery);
                $insertStmt->bindValue(':user_uuid', $user_uuid, PDO::PARAM_STR);
                $insertStmt->bindValue(':task_uuid', $task_uuid, PDO::PARAM_STR);
                $insertStmt->bindValue(':state', $state, PDO::PARAM_INT);
                $insertStmt->bindValue(':date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $insertStmt->execute();
            }

            // Если ответ верный - начисляем очки
            if ($isCorrect) {
                $updateScoreQuery = "UPDATE users SET score = score + :value 
                               WHERE uuid = :user_uuid";
                $updateScoreStmt = $this->connect->prepare($updateScoreQuery);
                $updateScoreStmt->bindValue(':value', $task['value'], PDO::PARAM_INT);
                $updateScoreStmt->bindValue(':user_uuid', $user_uuid, PDO::PARAM_STR);
                $updateScoreStmt->execute();
            }

            // Подтверждаем транзакцию
            $this->connect->commit();

            return $isCorrect;

        } catch (Exception $e) {
            // В случае ошибки откатываем изменения
            $this->connect->rollBack();
            error_log("Error verifying task: " . $e->getMessage());
            throw new RuntimeException("Failed to verify task: " . $e->getMessage());
        }
    }
}

/**
 * Фасад для доступа ко всем таблицам базы данных
 */
class DBUtils
{
    private static $instance;
    private CategoriesTable $categories;
    private UsersTable $users;
    private TasksTable $tasks;
    private ResultsTable $results;
    private BaseTable $table;

    private function __construct()
    {
        $this->categories = new CategoriesTable();
        $this->users = new UsersTable();
        $this->tasks = new TasksTable();
        $this->results = new ResultsTable();
        $this->table = new Table();
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function categories(): CategoriesTable
    {
        return $this->categories;
    }

    public function users(): UsersTable
    {
        return $this->users;
    }

    public function tasks(): TasksTable
    {
        return $this->tasks;
    }

    public function results(): ResultsTable
    {
        return $this->results;
    }

    public function table(): BaseTable
    {
        return $this->table;
    }
}