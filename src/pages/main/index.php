<?php
include_once "utils/account_utils.php";
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/main/home.css">
    <title>cVEX | Платформа для CTF-соревнований</title>
</head>
<body>
<div class="main" style="padding-bottom: 500px;">
    <div class="block_main">
        <div class="block_login">
            <h1>Найдите флаг<b>.</b><b>.</b><b>.</b></h1>
            <p>Платформа для обучения и совершенствования навыков в области информационной безопасности. Решайте задачи, находите уязвимости и получайте ценный опыт.</p>
            <div class="login_buttons <?php echo AccountUtils::is_signed_in()?"hidden":"";?>">
                <a href="/login"><button>Войти</button></a>
                <a href="/signup"><button>Регистрация</button></a>
            </div>
        </div>
        <div class="main_picture"><img src="/global/images/main_picture.svg" alt=""></div>
    </div>
    <br><br><br><br><br>
    <div class="tasks-category">
        <div class="task-category"><img src="/global/images/task-category/school.svg"
                                        alt="">
            <h1>Школьники</h1>
            <p>Начните свой путь в кибербезопасности с базовых задач, разработанных специально для начинающих. Идеально для знакомства с основными концепциями.</p>
            <hr>
        </div>
        <div class="task-category"><img src="/global/images/task-category/student.svg"
                                        alt="">
            <h1>Студенты</h1>
            <p>Задачи среднего уровня сложности для тех, кто уже знаком с основами. Углубите свои знания и подготовьтесь к профессиональным вызовам.</p>
            <hr>
        </div>
        <div class="task-category"><img src="/global/images/task-category/pro.svg"
                                        alt="">
            <h1>Профи</h1>
            <p>Сложные и реалистичные сценарии для опытных специалистов. Проверьте свои навыки в условиях, приближенных к реальным атакам.</p>
        </div>
    </div>
</div>
</body>
</html>