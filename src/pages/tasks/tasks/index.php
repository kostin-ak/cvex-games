<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/pages/tasks/tasks/css/tasks.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/pages/tasks/tasks/css/task_item.css">
    <title>Задания</title>
</head>
<body>

<div class="main">
    <div class="tasks-section">
        <div class="main-container">
            <!-- Фильтры с иконками -->
            <div class="filters-panel">
                <div class="filter-group search-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="search" class="search-input" placeholder="Поиск по названию...">
                </div>

                <div class="filter-group">
                    <i class="fas fa-filter filter-icon"></i>
                    <select id="category" class="styled-select">
                        <option value="">Все категории</option>
                        <!-- Категории будут загружены динамически -->
                    </select>
                </div>

                <div class="filter-group">
                    <i class="fas fa-signal difficulty-icon"></i>
                    <select id="difficulty" class="styled-select">
                        <option value="">Любая сложность</option>
                        <option value="1">Легко</option>
                        <option value="2">Средне</option>
                        <option value="3">Сложно</option>
                        <option value="4">Эксперт</option>
                    </select>
                </div>

                <button id="filter" class="apply-btn">
                    <i class="fas fa-sliders-h"></i> Применить
                </button>
            </div>

            <!-- Анимированный лоадер -->
            <div class="loading-overlay">
                <div class="loader-content">
                    <div class="spinner">
                        <div class="double-bounce1"></div>
                        <div class="double-bounce2"></div>
                    </div>
                    <div class="loading-message">Загрузка заданий...</div>
                </div>
            </div>

            <!-- Контейнер для заданий -->
            <div id="tasks-container" class="tasks-grid"></div>

            <!-- Пагинация с эффектом перехода -->
            <div id="pagination" class="pagination-wrapper"></div></div>
    </div>

    <script>
        // Текущие параметры фильтрации
        const state = {
            page: 1,
            category: '',
            difficulty: '',
            search: '',
            totalPages: 0
        };

        // Таймер для отложенного поиска
        let searchTimer = null;

        // Инициализация загрузчика задач
        async function loadTasks(page = state.page, category = state.category, difficulty = state.difficulty, search = state.search) {
            showLoading();
            $('#tasks-container').fadeOut(200);

            try {
                const response = await $.ajax({
                    url: '/server/tasks_nav.php',
                    type: 'GET',
                    data: { page, category, difficulty, search },
                    dataType: 'json'
                });

                console.log(response)

                if (response.error) {
                    showError(response.error);
                    return;
                }

                updateState(response, page, category, difficulty, search);
                renderTasks(response);
                updatePagination(response.totalPages);
                updateURL();

            } catch (error) {
                showError('Ошибка при загрузке задач');
            } finally {
                hideLoading();
                $('#tasks-container').fadeIn(300);
            }
        }

        // Обновление состояния
        function updateState(data, page, category, difficulty, search) {
            state.page = page;
            state.category = category;
            state.difficulty = difficulty;
            state.search = search;
            state.totalPages = data.totalPages || 0;
        }

        // Рендер задач
        function renderTasks(data) {
            const $container = $('#tasks-container').html(data.html || '');

            // Анимация появления карточек
            $container.find('.task').each(function(index) {
                $(this).delay(100 * index).fadeIn(200);

                // Добавьте создание частиц здесь
                const $card = $(this);
                if (!$card.find('.task-card-particles').length) { // Проверка, чтобы не создавать частицы повторно
                    const $particlesContainer = $('<div class="task-card-particles"></div>');
                    $card.append($particlesContainer);

                    const particleCount = Math.floor($card.width() / 8);

                    for (let i = 0; i < particleCount; i++) {
                        const size = Math.random() * 7 + 1;
                        const posX = Math.random() * $card.width();
                        const duration = Math.random() * 10 + 7;
                        const delay = Math.random() * -20;

                        $('<div class="task-card-particle"></div>')
                            .css({
                                'width': `${size}px`,
                                'height': `${size}px`,
                                'left': `${posX}px`,
                                'bottom': `-${size}px`,
                                'animation-duration': `${duration}s`,
                                'animation-delay': `${delay}s`
                            })
                            .appendTo($particlesContainer);
                    }
                }
            });
        }

        // Обновление пагинации
        function updatePagination(totalPages) {
            const $pagination = $('#pagination').empty();
            if (totalPages <= 1) return;

            const buttons = [];
            const maxVisible = 5;
            let startPage, endPage;

            if (totalPages <= maxVisible) {
                startPage = 1;
                endPage = totalPages;
            } else {
                const maxVisibleBefore = Math.floor(maxVisible / 2);
                const maxVisibleAfter = Math.ceil(maxVisible / 2) - 1;

                if (state.page <= maxVisibleBefore) {
                    startPage = 1;
                    endPage = maxVisible;
                } else if (state.page + maxVisibleAfter >= totalPages) {
                    startPage = totalPages - maxVisible + 1;
                    endPage = totalPages;
                } else {
                    startPage = state.page - maxVisibleBefore;
                    endPage = state.page + maxVisibleAfter;
                }
            }

            // Кнопка "Назад"
            if (state.page > 1) {
                buttons.push(`
                <button class="page-btn prev-next" data-page="${state.page - 1}">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `);
            }

            // Первая страница
            if (startPage > 1) {
                buttons.push(createPageButton(1));
                if (startPage > 2) buttons.push('<span class="dots">...</span>');
            }

            // Основные страницы
            for (let i = startPage; i <= endPage; i++) {
                buttons.push(createPageButton(i));
            }

            // Последняя страница
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) buttons.push('<span class="dots">...</span>');
                buttons.push(createPageButton(totalPages));
            }

            // Кнопка "Вперед"
            if (state.page < totalPages) {
                buttons.push(`
                <button class="page-btn prev-next" data-page="${state.page + 1}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `);
            }

            $pagination.html(buttons.join('')).fadeIn(300);
        }

        function createPageButton(page) {
            const active = page === state.page ? 'active' : '';
            return `
            <button class="page-btn ${active}" data-page="${page}">
                ${page}
            </button>
        `;
        }

        // Обработчики событий
        $(document)
            .on('click', '.page-btn', function() {
                scrollToTop();
                loadTasks($(this).data('page'));
            })
            .on('click', '#filter', applyFilters)
            .on('change', '#category, #difficulty', function() {
                // Обновляем поиск из поля ввода перед применением фильтров
                state.search = $('#search').val().trim();
                applyFilters();
            })
            .on('input', '#search', function() {
                // Только обновляем state.search, но не применяем фильтры
                state.search = $(this).val().trim();
            })
            .on('keypress', '#search', function(e) {
                // Поиск при нажатии Enter
                if (e.which === 13) {
                    applyFilters();
                }
            });

        // Инициализация страницы
        $(document).ready(function() {
            initFromURL();
            loadCategories();
        });

        // Вспомогательные функции
        function scrollToTop() {
            $('html, body').animate({ scrollTop: 0 }, 300);
        }

        function showLoading() {
            $('.loading-overlay').css('display', 'flex').hide().fadeIn(200);
        }

        function hideLoading() {
            $('.loading-overlay').fadeOut(200);
        }

        function showError(message) {
            const $error = $(`<div class="error-message">${message}</div>`);
            $('#tasks-container').html($error.fadeIn());
        }

        function applyFilters() {
            state.category = $('#category').val();
            state.difficulty = $('#difficulty').val();
            scrollToTop();
            loadTasks(1); // Сброс на первую страницу
        }

        function updateURL() {
            const params = new URLSearchParams();
            if (state.page > 1) params.set('page', state.page);
            if (state.category) params.set('category', state.category);
            if (state.difficulty) params.set('difficulty', state.difficulty);
            if (state.search) params.set('search', state.search);

            history.replaceState(null, '', params.toString() ? `?${params}` : location.pathname);
        }

        function initFromURL() {
            const params = new URLSearchParams(location.search);
            state.page = parseInt(params.get('page')) || 1;
            state.category = params.get('category') || '';
            state.difficulty = params.get('difficulty') || '';
            state.search = params.get('search') || '';

            $('#category').val(state.category);
            $('#difficulty').val(state.difficulty);
            $('#search').val(state.search);
        }

        // Загрузка категорий
        function loadCategories() {
            $.ajax({
                url: '/server/categories.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) return showError(data.error);
                    data.categories.forEach(cat => {
                        $('#category').append(`<option value="${cat.uuid}">${cat.name}</option>`);
                    });
                },
                error: () => showError('Ошибка загрузки категорий')
            });
        }
    </script>
    <script>
        $(window).on('load', function(){
            loadTasks();
        });
    </script>

</div>
</body>
</html>