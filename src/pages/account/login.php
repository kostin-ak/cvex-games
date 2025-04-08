<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="/global.css">
    <link rel="stylesheet" href="/pages/account/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pages/account/js/login.js" defer></script>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="error-message" id="error-message">
            Неверно введен логин или пароль!
        </div>

        <h1 class="login-header">Вход в систему</h1>

        <form id="login-form">
            <div class="field-group">
                <label for="username" class="field-label">Логин или email</label>
                <input type="text" id="username" name="username" class="field-input"
                       placeholder="Введите ваш логин или email" autofocus
                       pattern="[a-zA-Z0-9@._-]{3,}" title="Минимум 3 символа (буквы, цифры, @ . _ -)"
                       oninput="toggleValidationStyles(this)"/>
            </div>

            <div class="field-group">
                <label for="password" class="field-label">Пароль</label>
                <input type="password" id="password" name="password"
                       class="field-input" placeholder="Введите ваш пароль" required
                       minlength="4" title="Минимум 4 символа">
                <span class="password-toggle material-icons" id="toggle-password">visibility</span>
            </div>

            <input type="hidden" id="link" name="link" value="<?php echo htmlspecialchars($_GET['link'] ?? '/'); ?>">

            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">Войти</button>
                <button type="button" onclick="window.location.href='/signup'"
                        class="btn btn-secondary">Зарегистрироваться</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>