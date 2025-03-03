<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/account/login.css">
    <script src="/global/js/functions.js"></script>
    <title>Вход в систему</title>
</head>
<body>
    <div class="main">
        <div class="main-content">
            <div class="registration-form">
                <h1>Вход в систему</h1>
                <div class="form-group">
                    <label for="email">Email или логин</label>
                    <input type="email" id="email" name="email" placeholder="Email или логин">
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" placeholder="Пароль">
                </div>
                <div class="form-group">
                    <button type="submit">Войти</button>
                    <button onclick="window.location.replace('/signup');">Зарегистироваться</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>