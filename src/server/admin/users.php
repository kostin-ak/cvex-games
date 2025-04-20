<?php
header('Content-Type: application/json');
include_once "../../configs/config.php";
require_once '../../utils/db_utils.php';
include_once '../../utils/account_utils.php';

$db = DBUtils::getInstance();
$method = $_SERVER['REQUEST_METHOD'];

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!AccountUtils::is_admin()){
        throw new Exception("The user must be an administrator");
    }

    switch ($method) {
        case 'GET':
            if (isset($_GET['uuid'])){
                $user = DBUtils::getInstance()->users()->getUserByUuid($_GET['uuid']);
                echo json_encode([
                    'success' => true,
                    'data' => $user
                ]);
            }else {
                $users = DBUtils::getInstance()->users()->searchUsers($_GET['search']);
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'users' => $users
                    ]
                ]);
            }
            break;
        case 'POST':
            if (isset($_POST['uuid'])){
                try {
                    $result = DBUtils::getInstance()->users()->updateUser($_POST['uuid'], $_POST);
                    $message = "ok";
                }catch (Exception $e){
                    $result = false;
                    $message = $e->getMessage();
                }

                echo json_encode([
                    'success' => $result,
                    'message' => $message
                ]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: '.$e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}