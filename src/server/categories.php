<?php

include_once "../configs/config.php";
include_once "../utils/db_utils.php";
include_once ROOT."/utils/account_utils.php";
include_once "../entities/models/Task.php";

function getCategories() {
    $db = DBUtils::getInstance();
    if (!AccountUtils::is_signed_in())
        $stmt = $db->getConnection()->query("SELECT * FROM categories WHERE is_public = true ORDER BY name ASC");
    else
        $stmt = $db->getConnection()->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    $categories = getCategories();
    echo json_encode(['categories' => $categories]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>