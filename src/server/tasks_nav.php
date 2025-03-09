<?php

include_once "../configs/config.php";
include_once "../utils/db_utils.php";
include_once "../utils/account_utils.php";

function generateHTML($tasks) {
    $html = '';
    foreach ($tasks as $task) {

        if ($task == null) continue;

        $html .= '<div class="task card">';
        $html .= "<a href='/task?uuid={$task->getUuid()}'>";
        $html .= '<h3>' . htmlspecialchars($task->getName()) . '</h3>';
        $html .= '<p>' . htmlspecialchars($task->getDescription()) . '</p>';
        $html .= '<p><strong>Создано:</strong> ' . htmlspecialchars($task->getCreate()) . '</p>';
        $html .= '<p><strong>Категория:</strong> ' . htmlspecialchars($task->getCategoryObj()->getName()) . '</p>';
        $html .= '<p><strong>Сложность:</strong> ' . htmlspecialchars($task->getDifficulty()) . '</p>';
        $html .= "</a>";
        $html .= '</div>';
    }
    return $html;
}

try {
    //sleep(5);

    $limit = 5; // Количество записей на странице
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : null;

    $db = DBUtils::getInstance();
    $tasks = $db->getTasks($page, $limit, $category, $difficulty, AccountUtils::is_signed_in(), AccountUtils::is_admin());

    // Проверяем, что $tasks является массивом
    if (!is_array($tasks)) {
        $tasks = []; // Если по какой-то причине это не массив, инициализируем его как пустой
    }

    $totalPages = ceil($db->getTotalPages($limit, $category, $difficulty, AccountUtils::is_signed_in(), AccountUtils::is_admin()));
    $html = generateHTML($tasks);

    echo json_encode(['html' => $html, 'totalPages' => $totalPages]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>