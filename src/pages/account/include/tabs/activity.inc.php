<div>
    <?php
        var_dump(DBUtils::getInstance()->results()->getCompletedByUser($_SESSION['user_uuid']));
    ?>
</div>