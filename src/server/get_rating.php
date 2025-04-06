<?php
    include_once "../configs/config.php";
    header('Content-Type: application/json');
    include_once "../utils/db_utils.php";
    session_start();

    sleep(2);

    try {
        $group = isset($_GET['group']) && $_GET['group'] === 'my' && isset($_SESSION['user']['group'])
            ? $_SESSION['user']['group']
            : null;

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
        $users = DBUtils::getInstance()->users()->getRatingByGroup($group, $limit);

        // Prepare response
        $response = [
            'success' => true,
            'users' => array_map(function($user) {
                return [
                    'rank' => $user['rank'],
                    'username' => $user['username'],
                    'name' => $user['name'] ?? '',
                    'sname' => $user['sname'] ?? '',
                    'score' => $user['score']
                ];
            }, $users)
        ];
    } catch (Exception $e) {
        http_response_code(500);
        $response = [
            'success' => false,
            'message' => 'Произошла ошибка при получении данных рейтинга'
        ];
    }

    echo json_encode($response);