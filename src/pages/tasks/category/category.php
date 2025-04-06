<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/global.css">
    <link rel="stylesheet" href="/pages/tasks/category/css/category.css">
    <link rel="stylesheet" href="/pages/tasks/category/css/single_category.css">
    <title>Категория</title>
</head>
<body>
<div class="category-page">
    <div class="main">
        <?php
        if ($_GET['uuid'] == "in-dev") {
            echo '<div class="category-single">
                        <div class="category-card in-dev">
                            <div class="particles" id="particles-js"></div>
                            <div class="category-header">
                                <h1 class="category-title">Категория в разработке</h1>
                                <span class="badge badge-dev">В разработке</span>
                            </div>
                            <div class="category-description">
                                <p>Эта категория пока недоступна. Мы работаем над ее наполнением.</p>
                            </div>
                            <div class="category-actions">
                                <button class="tasks-button" disabled>Перейти к заданиям</button>
                            </div>
                        </div>
                    </div>';
        } else {
            include_once "configs/config.php";
            include_once "utils/db_utils.php";

            $category = DBUtils::getInstance()->categories()->getByUUID($_GET['uuid']);
            $category_uuid = htmlspecialchars($_GET['uuid'] ?? '');

            if (!$category) {
                echo '<div class="category-single">
                            <div class="category-card">
                                <div class="particles" id="particles-js"></div>
                                <div class="category-header">
                                    <h1 class="category-title">Категория не найдена</h1>
                                </div>
                                <div class="category-description">
                                    <p>Извините, запрошенная категория не существует или была удалена.</p>
                                </div>
                                <div class="category-actions">
                                    <a href="/tasks" class="tasks-button">
                                        <i class="material-icons">arrow_back</i> К списку категорий
                                    </a>
                                </div>
                            </div>
                        </div>';
            } else {
                echo '<div class="category-single">
                            <div class="category-card' . ($category['in_dev'] ? ' in-dev' : '') . '">
                                <div class="particles" id="particles-js"></div>
                                <div class="category-header">
                                    <h1 class="category-title">' . htmlspecialchars($category['name']) . '</h1>';

                if ($category['in_dev']) {
                    echo '<span class="badge badge-dev">В разработке</span>';
                }
                if (!$category['is_public']) {
                    echo '<span class="badge badge-private">Приватная</span>';
                }

                echo '</div>';

                if ($category['image']) {
                    echo '<div class="category-image-container">
                                <img src="' . htmlspecialchars($category['image']) . '" alt="' .
                        htmlspecialchars($category['name']) . '" class="category-image' .
                        ($category['in_dev'] ? ' blur-filter' : '') . '">
                            </div>';
                }

                if ($category['description']) {
                    echo '<div class="category-description">
                                ' . nl2br(htmlspecialchars($category['description'])) . '
                            </div>';
                }

                echo '<div class="category-actions">
                            <a href="/tasks?category=' . $category_uuid . '" class="tasks-button" ' .
                    ($category['in_dev'] ? ' disabled' : '') . '>
                                <i class="material-icons">task_alt</i> Перейти к заданиям
                            </a>
                        </div>';

                echo '</div></div>';
            }
        }
        ?>
    </div>
</div>

<script>
    // Создание частиц для фона
    document.addEventListener('DOMContentLoaded', function() {
        const particlesContainers = document.querySelectorAll('.particles');

        particlesContainers.forEach(container => {
            const particleCount = Math.floor(window.innerWidth / 10);

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Случайные параметры частицы
                const size = Math.random() * 5 + 1;
                const posX = Math.random() * container.offsetWidth;
                const duration = Math.random() * 10 + 7;
                const delay = Math.random() * -20;

                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}px`;
                particle.style.bottom = `-${size}px`;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;

                container.appendChild(particle);
            }
        });
    });
</script>
</body>
</html>