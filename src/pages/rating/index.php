<?php
include_once "utils/db_utils.php";
include_once "utils/account_utils.php";
session_start();

$isSignedIn = AccountUtils::is_signed_in();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Рейтинг</title>
    <link rel="stylesheet" href="/pages/rating/css/rating.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="main">
    <div class="container">
        <h1>Рейтинг пользователей</h1>

        <?php if ($isSignedIn): ?>
            <div class="group-selector">
                <button id="my-group-btn" class="<?= $_GET['group'] === 'my' ? 'active' : '' ?>">Моя группа</button>
                <button id="all-btn" class="<?= !isset($_GET['group']) || $_GET['group'] !== 'my' ? 'active' : '' ?>">Общий рейтинг</button>
            </div>
        <?php endif; ?>

        <!-- Rest of the HTML remains the same -->
        <div class="dynamic-content">
            <div class="loading-container">
                <div class="loader">
                    <div class="loader-circle"></div>
                    <div class="loader-circle"></div>
                    <div class="loader-circle"></div>
                    <div class="loader-circle"></div>
                </div>
                <div class="loading-text">Загрузка рейтинга...</div>
            </div>



            <div class="error-message">Ошибка загрузки данных. Попробуйте еще раз.</div>


            <div id="rating-container" style="display: none;">
                <table class="rating-table">
                <thead>
                <tr>
                    <th>Место</th>
                    <th>ID аккаунта</th>
                    <th>ФИО</th>
                    <th class="score">Очки</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $group = isset($_GET['group']) && $_GET['group'] === 'my' && isset($_SESSION['user']['group'])
                    ? $_SESSION['user']['group']
                    : null;

                $users = DBUtils::getInstance()->users()->getRatingByGroup($group, 25);

                foreach ($users as $user) {
                    $fullName = trim(htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['sname']));
                    echo '<tr class="clickable-row" data-user="' . htmlspecialchars($user['username']) . '">';
                    echo '<td class="rank">' . htmlspecialchars($user['rank']) . '</td>';
                    echo '<td>#' . htmlspecialchars($user['username']) . '</td>';
                    echo '<td>' . $fullName . '</td>';
                    echo '<td class="score">' . htmlspecialchars($user['score']) . '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        <?php if ($isSignedIn): ?>
        loadRating('my')
        <?php else: ?>
        loadRating('all');
        <?php endif; ?>

        // Initialize the rating table
        const currentView = window.location.search.includes('group=my') ? 'my' : 'all';

        // Row click handler (event delegation)
        $('#rating-container').on('click', '.clickable-row', function() {
            window.location.href = '/user/' + encodeURIComponent($(this).data('user'));
        });

        // Prevent text selection when clicking
        $('#rating-container').on('mousedown', '.clickable-row', function(e) {
            if (e.detail > 1) {
                e.preventDefault();
            }
        });

        // Group selector buttons
        function loadRating(view) {
            <?php if (!$isSignedIn): ?>
            view = 'all';
            <?php endif; ?>

            $('.group-selector button').removeClass('active');
            $(view === 'my' ? '#my-group-btn' : '#all-btn').addClass('active');
            updateUrl(view === 'my' ? 'group=my' : '');

            // Показываем лоадер
            $('.loading-container').removeClass('hidden');
            $('#rating-container').css('display', 'none').removeClass('visible');
            $('.error-message').hide();

            $.ajax({
                url: '/server/get_rating.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    group: view === 'my' ? 'my' : '',
                    limit: 25
                },
                success: function(data) {
                    if (data.success) {
                        // Подготовка таблицы
                        renderRatingTable(data.users);

                        // Плавное переключение
                        $('.loading-container').addClass('hidden');
                        $('#rating-container').css('display', 'block');
                        setTimeout(() => {
                            $('#rating-container').addClass('visible');
                        }, 30);
                    } else {
                        showError(data.message || 'Ошибка загрузки данных');
                    }
                },
                error: setTimeout(function(xhr) {
                    showError(xhr.responseJSON?.message || 'Ошибка соединения');
                }, 300)
            });
        }

        function renderRatingTable(users) {
            const $table = $('<table>').addClass('rating-table');
            const $thead = $('<thead>').appendTo($table);
            const $tbody = $('<tbody>').appendTo($table);

            $thead.append(`
        <tr>
            <th>Место</th>
            <th>ID аккаунта</th>
            <th>ФИО</th>
            <th class="score">Очки</th>
        </tr>
    `);

            users.forEach(user => {
                const fullName = [user.name, user.sname].filter(Boolean).join(' ');
                const $row = $(`
            <tr class="clickable-row" data-user="${escapeHtml(user.username)}">
                <td class="rank">${escapeHtml(user.rank)}</td>
                <td>#${escapeHtml(user.username)}</td>
                <td>${escapeHtml(fullName)}</td>
                <td class="score">${escapeHtml(user.score)}</td>
            </tr>
        `);
                $tbody.append($row);
            });

            $('#rating-container').empty().append($table);
        }

        function escapeHtml(unsafe) {
            if (unsafe === undefined || unsafe === null) return '';
            return unsafe.toString()
                .replace(/&/g, "&")
                .replace(/</g, "<")
                .replace(/>/g, ">")
                .replace(/"/g, "\"")
                .replace(/'/g, "'");
        }

        function updateUrl(params) {
            const url = params ? `${window.location.pathname}?${params}` : window.location.pathname;
            history.pushState({}, '', url);
        }

        function showError(message) {
            $('.error-message').text(message || 'Ошибка загрузки данных').show();
            $('.loading-indicator').hide();
            $('#rating-container').css('opacity', '1');
        }

        // Button click handlers
        $('#my-group-btn').on('click', function() {
            if (!$(this).hasClass('active')) {
                loadRating('my');
            }
        });

        $('#all-btn').on('click', function() {
            if (!$(this).hasClass('active')) {
                loadRating('all');
            }
        });

        // Handle browser back/forward
        window.addEventListener('popstate', function() {
            const isMyGroup = window.location.search.includes('group=my');
            loadRating(isMyGroup ? 'my' : 'all');
        });
    });
</script>
</body>
</html>