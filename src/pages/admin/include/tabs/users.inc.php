<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/admin/include/tabs/css/users.css">
    <title>cVEX | Админ-панель | Пользователи</title>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Управление пользователями</h1>
        </div>
        <div class="user-finder">
            <div class="search-container">
                <input type="text" id="userSearch" placeholder="Поиск пользователей...">
                <button id="searchButton"><span class="material-icons">search</span></button>
            </div>
        </div>
        <div class="users-list">
            <!-- Users will be loaded here via AJAX -->
        </div>
    </div>

    <script src="/pages/admin/include/tabs/js/users.js"></script>
</body>
</html>