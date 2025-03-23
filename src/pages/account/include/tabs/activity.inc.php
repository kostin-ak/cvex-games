<div>
    <?php
        var_dump(DBUtils::getInstance()->getCompletedTasksByUser($_SESSION['user_uuid']));
    ?>
</div>