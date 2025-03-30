<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/tasks/category/css/category.css">
    <title>Категория</title>
</head>
<body>
    <div class="main">
        <?php
            if ($_GET['uuid'] == "in-dev") {
                echo "<h1>Категория в разработке</h1>";
            }
            else{
                include_once "configs/config.php";
                include_once "utils/db_utils.php";

                var_dump(DBUtils::getInstance()->categories()->getByUUID($_GET['uuid']));
            }
        ?>
    </div>
</body>
</html>