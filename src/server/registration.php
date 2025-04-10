<?php
include_once "../configs/config.php";
include_once "../utils/db_utils.php";
include_once "../utils/account_utils.php";
try{
    $options = [
        'cost' => 12,
    ];
    $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
    $_POST['group'] = (int) $_POST['group'];

    DBUtils::getInstance()->users()->createUser($_POST);

    echo json_encode(array("success" => true));
} catch (Exception $e){
    echo json_encode(array('error' => $e->getMessage()));
}