<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/pages/tasks/tasks/css/tasks.css">
    <title>Задания</title>
    <style>

    </style>
</head>
<body>

<div class="main">
    <div class="finder">
        <div>
            <label for="category">Категория:</label>
            <select id="category" class="select">
                <option value="">Все</option>
                <!-- Здесь будут загружены категории -->
            </select>
        </div>

        <div>
            <label for="difficulty">Сложность:</label>
            <select id="difficulty" class="select">
                <option value="">Все</option>
                <option value="1">Легко</option>
                <option value="2">Средне</option>
                <option value="3">Сложно</option>
                <option value="4">Невозможно</option>
            </select>
        </div>

        <button id="filter">Применить фильтр</button>
    </div>

    <div id="loading" style="display: none;">Загрузка <span>.</span><span>.</span><span>.</span>  </div>

    <div id="tasks" style="display: none;"></div>

    <div id="pagination" class="pagination" style="display: none;"></div>
</div>

<script>
    let currentPage = 1;
    let selectedCategory = '';
    let selectedDifficulty = '';
    let totalPages = 0;

    function loadTasks(page, selected_category, selected_difficulty) {
        if (selected_category == undefined) selected_category = selectedCategory;
        if (selected_difficulty == undefined) selected_difficulty = selectedDifficulty;

        // Показываем индикатор загрузки
        $('#loading').fadeIn();

        $.ajax({
            url: '/server/tasks_nav.php',
            type: 'GET',
            data: {
                page: page,
                category: selected_category,
                difficulty: selected_difficulty
            },
            dataType: 'json',
            success: function(data) {
                // Скрываем индикатор загрузки
                $('#loading').fadeOut();

                //console.log(data)

                if (data.error) {
                    alert(data.error);
                    return;
                }
                $('#tasks').html(data.html);
                totalPages = data.totalPages;
                setupPagination(data.totalPages);
                // Обновляем URL
                let url = `?page=${page}`;
                if (selectedCategory) {
                    url += `&category=${selectedCategory}`;
                }
                if (selectedDifficulty) {
                    url += `&difficulty=${selectedDifficulty}`;
                }
                history.pushState(null, '', url);
                $("#pagination").fadeIn();
                $("#tasks").fadeIn()
            },
            error: function() {
                // Скрываем индикатор загрузки
                $('#loading').fadeOut();

                alert('Ошибка при загрузке задач.');
            }
        });
    }

    function setupPagination(totalPages) {
        $('#pagination').empty();

        const maxButtons = 5;

        if (totalPages <= maxButtons) {
            for (let i = 1; i <= totalPages; i++) {
                $('#pagination').append(`<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`);
            }
        } else {
            // Стрелка влево
            if (currentPage > 1) {
                $('#pagination').append(`<button class="page-btn" data-page="${currentPage - 1}">&laquo;</button>`);
            }

            $('#pagination').append(`<button class="page-btn ${currentPage === 1 ? 'active' : ''}" data-page="1">1</button>`);

            if (currentPage > 3) {
                $('#pagination').append(`<span>...</span>`);
            }

            let startPage = Math.max(2, currentPage - 1);
            let endPage = Math.min(totalPages - 1, currentPage + 1);

            for (let i = startPage; i <= endPage; i++) {
                $('#pagination').append(`<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`);
            }

            if (currentPage < totalPages - 2) {
                $('#pagination').append(`<span>...</span>`);
            }

            $('#pagination').append(`<button class="page-btn ${currentPage === totalPages ? 'active' : ''}" data-page="${totalPages}">${totalPages}</button>`);

            // Стрелка вправо
            if (currentPage < totalPages) {
                $('#pagination').append(`<button class="page-btn" data-page="${currentPage + 1}">&raquo;</button>`);
            }
        }
    }

    $(document).on('click', '.page-btn', function() {
        $("#loading").fadeIn();
        $("#tasks").fadeOut();
        window.scrollTo(0, 0);
        currentPage = $(this).data('page');
        loadTasks(currentPage);
    });

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page');
        selectedCategory = urlParams.get('category') || '';
        selectedDifficulty = urlParams.get('difficulty') || '';

        $('#category').val(selectedCategory);
        $('#difficulty').val(selectedDifficulty);

        currentPage = page ? parseInt(page) : 1;

        loadTasks(page ? parseInt(page) : currentPage);
    });

    // Обработка состояния при навигации
    window.onpopstate = function(event) {
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page');
        selectedCategory = urlParams.get('category') || '';
        selectedDifficulty = urlParams.get('difficulty') || '';

        $('#category').val(selectedCategory);
        $('#difficulty').val(selectedDifficulty);

        currentPage = page ? parseInt(page) : 1;
        loadTasks(currentPage);

    };

    // Загрузка категорий
    function loadCategories() {
        $.ajax({
            url: '/server/categories.php', // Предполагается, что у вас есть этот файл
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                //console.log(data);
                if (data.error) {
                    alert(data.error);
                    return;
                }
                data.categories.forEach(category => {
                    $('#category').append(`<option value="${category.uuid}">${category.name}</option>`);
                });
            },
            error: function() {
                alert('Ошибка при загрузке категорий.');
            }
        });
    }

    $('#filter').on('click', function() {
        $("#loading").fadeIn();
        $("#tasks").fadeOut();
        selectedCategory = $('#category').val();
        selectedDifficulty = $('#difficulty').val();
        loadTasks(1); // Сброс страницы на 1 при применении фильтров
    });

    $('.select').on('change', function() {
        $("#loading").fadeIn();
        $("#tasks").fadeOut();
        selectedCategory = $('#category').val();
        selectedDifficulty = $('#difficulty').val();
        loadTasks(1); // Сброс страницы на 1 при применении фильтров
    });

    // Загрузка категорий при загрузке страницы
    $(document).ready(function() {
        loadCategories();
    });
</script>

</body>
</html>