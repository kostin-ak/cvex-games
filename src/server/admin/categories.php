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
            if (count($_GET) == 0) {
                $categories = $db->categories()->getList();
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'categories' => $categories  // Wrap categories in a 'data.categories' array
                    ]
                ]);
            }
            break;
        case 'POST':
            if (isset($_GET['change'])){
                if (isset($_POST['uuid']) and $_POST['uuid'] != null and $_POST['uuid'] != ""){

                    $db->categories()->updateCategory($_POST['uuid'], $_POST);

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'state' => "updated"
                        ]
                    ]);
                }else{

                    $db->categories()->addCategory($_POST);

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'state' => "added"
                        ]
                    ]);
                }
            }
            else if(isset($_GET['delete'])){

                $db->categories()->deleteCategory($_POST['uuid']);

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'state' => "deleted"
                    ]
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