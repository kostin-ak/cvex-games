<?php

include_once "../configs/config.php";
include_once "../utils/db_utils.php";
include_once "../utils/account_utils.php";

function parseDifficulty($difficulty) {
    switch ((int)$difficulty) {
        case 1:
            return 'легко';
        case 2:
            return 'средняя';
        case 3:
            return 'сложно';
        case 4:
            return 'эксперт';
        default:
            return 'средне'; // Значение по умолчанию
    }
}

function formatDate($dateString) {
    if (empty($dateString)) {
        return '';
    }

    try {
        $date = new DateTime($dateString);
        return $date->format('d.m.Y');
    } catch (Exception $e) {
        return $dateString;
    }
}

function generateHTML($tasks) {
    $html = '';
    foreach ($tasks as $task) {
        if ($task == null) continue;

        $html .= '<div class="task card">';
        $html .= '<a href="/task?uuid=' . $task->getUuid() . '" class="text-decoration-none card-body-link">';
        $html .= '<div class="card-body">';

        // Заголовок
        $html .= '<h3 class="task-title">' . htmlspecialchars($task->getName()) . '</h3>';

        // Описание
        $html .= '<p class="task-description">' . htmlspecialchars($task->getDescription()) . '</p>';

        // Мета-информация
        $html .= '<div class="task-meta">';

        // Контейнер для даты
        $html .= '<div class="meta-group">';
        // Дата создания
        $html .= '<span class="meta-item date">';
        $html .= '<i class="material-icons meta-icon">event</i>';
        $html .= '<small>' . htmlspecialchars(formatDate($task->getCreate())) . '</small>';
        $html .= '</span>';
        $html .= '</div>'; // закрываем meta-group для даты

        // Сложность (используем функцию parseDifficulty)
        $difficultyNumber = $task->getDifficulty();
        $difficultyText = parseDifficulty($difficultyNumber);
        $difficultyLower = strtolower($difficultyText);
        $difficultyClass = 'task-difficulty--medium'; // По умолчанию

        if (strpos($difficultyLower, 'легко') !== false) {
            $difficultyClass = 'task-difficulty--easy';
        } elseif (strpos($difficultyLower, 'сложно') !== false) {
            $difficultyClass = 'task-difficulty--hard';
        } elseif (strpos($difficultyLower, 'эксперт') !== false) {
            $difficultyClass = 'task-difficulty--kill';
        }

        // Контейнер для тегов (категория и сложность)
        $html .= '<div class="meta-group">';

        // Категория
        $html .= '<span class="meta-item task-category">';
        $html .= '<i class="material-icons meta-icon">tag</i><span class="category-tag-text">';
        $html .= htmlspecialchars($task->getCategoryObj()->getName());
        $html .= '</span></span>';

        // Разделитель
        $html .= '<span class="meta-divider mx-1">·</span>';


        // Сложность
        $html .= '<span class="meta-item task-difficulty ' . $difficultyClass . '">';
        $html .= '<i class="material-icons meta-icon">speed</i>';
        $html .= htmlspecialchars($difficultyText);
        $html .= '</span>';

        // Добавляем тег "ограниченный по времени", если время ограничено
        if ($task->getTimeLimit() != 0) {
            $html .= '<span class="meta-item task-time-limit">';
            $html .= '<i class="material-icons meta-icon">timer</i>';
            $html .= 'Лимитированный';
            $html .= '</span>';
            $html .= '<span class="meta-divider mx-1">·</span>';
        }


        $html .= '</div>'; // закрываем meta-group для тегов
        $html .= '</div>'; // закрываем task-meta

        $html .= '</div>'; // закрываем card-body
        $html .= '</a>'; // закрываем ссылку
        $html .= '</div>'; // закрываем task card
    }
    return $html;
}
try {
    //sleep(5);

    $limit = 16; // Количество записей на странице
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : null;

    $db = DBUtils::getInstance();
    $tasks = $db->tasks()->getList($page, $limit, $category, (int) $difficulty, AccountUtils::is_signed_in(), AccountUtils::is_admin());

    // Проверяем, что $tasks является массивом
    if (!is_array($tasks)) {
        $tasks = []; // Если по какой-то причине это не массив, инициализируем его как пустой
    }

    $totalPages = ceil($db->tasks()->getTotalPages($limit, $category, (int) $difficulty, AccountUtils::is_signed_in(), AccountUtils::is_admin()));
    $html = generateHTML($tasks);

    echo json_encode(['html' => $html, 'totalPages' => $totalPages]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>