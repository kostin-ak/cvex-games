<?php

include_once "../configs/config.php";
include_once "../utils/db_utils.php";
include_once '../utils/account_utils.php';
include_once "../utils/icon_chooser.php";

$method = $_SERVER['REQUEST_METHOD'];

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

function getTaskFilesBlock(Task $task){
    $text = "";
    if ($task->getAttachment() != ""){
        $attachment = $task->getAttachment();
        $attachment = explode(";", $attachment);

        $text = '<hr><div><h2>Файлы</h2><div class="files">';
        foreach ($attachment as $a){
            $filename = explode("/",$a);
            $filename = $filename[count($filename)-1];
            $type = explode(".", $filename);
            if (count($type) >= 1){
                $type = $type[count($type) - 1];
            }else{
                $type = "zip";
            }

            $text .= '
                    <div class="file">
                        <img src="'.IconChooser::chooseIcon($type).'" alt="">
                        <div>
                            <p class="file_name">'.$filename.'</p>
                            <a href="'.$a.'"
                               class="download" download>Скачать</a>
                        </div>
                    </div>   
                    ';
        }
        $text .= '</div></div>';

    }
    return $text;
}

function renderTask($task){
    if ($task != null) {
        $text = '<p>'.$task->getTaskText().'</p>' . getTaskFilesBlock($task);
        return $text;
    }
    return false;
}

function getState($uuid){

    if (isset($_SESSION['user_uuid'])){
        $user = $_SESSION['user_uuid'];
        $task = DBUtils::getInstance()->results()->getByTaskAndUser($uuid, $user);
        return $task->getState() == 2 ? renderTask($task->getTask()) : false;
    }
    return false;
}

function startTask($uuid){
    if (isset($_SESSION['user_uuid'])){
        $user = $_SESSION['user_uuid'];
        $task = DBUtils::getInstance()->results()->startTask($user, $uuid);
        return renderTask($task->getTask());
    }
}

function checkAnswer($uuid, $answer){
    if (isset($_SESSION['user_uuid'])){
        $user = $_SESSION['user_uuid'];
        return DBUtils::getInstance()->results()->verifyAndRecordTask($user, $uuid, $answer);
    }
}


try {
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            if (isset($_GET['state'])){
                echo json_encode([
                    'success' => true,
                    'data' => getState($_GET['task_uuid']) ?? false
                ]);
            }
            else if (isset($_GET['start'])){
                echo json_encode([
                    'success' => true,
                    'data' => startTask($_GET['task_uuid'])
                ]);
            }
            else if (isset($_GET['check'])){
                echo json_encode([
                    'success' => true,
                    'data' => checkAnswer($_GET['task_uuid'], $_GET['answer'])
                ]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: '.$e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

