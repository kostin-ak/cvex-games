<?php
    if ($_SERVER['REQUEST_URI'] != "/errors/403"){
        header("Location: /errors/403");
    }else{
        header("HTTP/1.1 403 Forbidden");
    }
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ошибка 403</title>
</head>
<body>
    <h1>403</h1>
</body>
</html>