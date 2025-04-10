<?php
include_once "../configs/config.php";
include_once "../utils/db_utils.php";
include_once "../utils/account_utils.php";
$result = array(
    "available" => false
);

if (isset($_GET['check-username'])){
    $exist = DBUtils::getInstance()->users()->exists($_POST['username'], "username");
    $result['available'] = !$exist;
}

else if (isset($_GET['check-email'])){
    $exist = DBUtils::getInstance()->users()->exists($_POST['email'], "email");
    $result['available'] = !$exist;
}

echo json_encode($result);