<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>cVEX | Админ-панель | Категории</title>
    <link rel="stylesheet" href="/pages/admin/include/tabs/css/category.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h1>Управление категориями</h1>
        <button id="addCategoryBtn" class="btn-primary">Добавить категорию</button>
    </div>

    <div class="categories" id="categoriesContainer">
        <!-- Categories will be loaded here via AJAX -->
    </div>

</div>

<script src="/pages/admin/include/tabs/js/category.js"></script>
</body>
</html>