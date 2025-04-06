<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/global.css">
    <link rel="stylesheet" href="/pages/tasks/category/css/category.css">
    <title>Категория</title>
    <style>
        /* Основные стили */
        .category-page {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: var(--spacing--large);
            background: linear-gradient(135deg,
            rgba(var(--background-color--primary--alpha), 0.8) 0%,
            rgba(var(--background-color--secondary--alpha), 0.5) 100%);
        }

        /* Главный контейнер */
        .category-single {
            position: relative;
            max-width: 1200px;
            width: 90%;
            margin: 50px auto;
            filter: drop-shadow(0 25px 25px rgba(0,0,0,0.15));
        }

        /* Карточка категории */
        .category-card {
            position: relative;
            background: linear-gradient(
                    145deg,
                    var(--background-color--secondary) 0%,
                    var(--background-color--primary) 100%
            );
            border-radius: 25px;
            overflow: hidden;
            padding: 40px;
            box-shadow:
                    0 20px 60px rgba(0,0,0,0.15),
                    inset 0 0 0 1px rgba(255,255,255,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0,0,0,0.2);
        }

        /* Заголовок с неоновым эффектом */
        .category-header {
            position: relative;
            margin-bottom: 40px;
            z-index: 2;
        }

        .category-title {
            font-size: 3rem;
            color: var(--text-color--primary);
            margin: 0 0 20px;
            text-shadow: 0 2px 10px rgba(var(--color--primary--alpha), 0.3);
            position: relative;
            display: inline-block;
        }

        .category-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(
                    90deg,
                    var(--color--primary),
                    var(--color--accent),
                    transparent
            );
            opacity: 0.7;
        }

        /* Изображение */
        .category-image-container {
            position: relative;
            width: 100%;
            margin: 40px 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            transition: transform 0.5s ease;
        }

        .category-card:hover .category-image-container {
            transform: scale(1.01);
        }

        .category-image {
            display: block;
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.03);
        }

        /* Описание с эффектом стекла */
        .category-description {
            position: relative;
            padding: 30px;
            background: rgba(var(--background-color--primary--alpha), 0.7);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.5s ease;
            z-index: 2;
        }

        .category-description:hover {
            background: rgba(var(--background-color--secondary--alpha), 0.8);
            transform: translateY(-5px);
        }

        /* Бейджи с анимацией */
        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-left: 15px;
            position: relative;
            overflow: hidden;
            animation: float 3s ease-in-out infinite;
        }

        .badge::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                    to bottom right,
                    rgba(255,255,255,0.3) 0%,
                    rgba(255,255,255,0) 50%
            );
            transform: rotate(30deg);
        }

        .badge-dev {
            background: linear-gradient(
                    135deg,
                    var(--color--error--basic) 0%,
                    #ff3f3f 100%
            );
            color: var(--text-color--light);
            box-shadow: 0 5px 15px rgba(255,0,0,0.3);
        }

        .badge-private {
            background: linear-gradient(
                    135deg,
                    var(--color--accent) 0%,
                    #6a11cb 100%
            );
            color: var(--text-color--light);
            box-shadow: 0 5px 15px rgba(106,17,203,0.3);
        }

        /* Кнопка с анимированным градиентом */
        .category-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }

        .tasks-button {
            position: relative;
            background: linear-gradient(
                    135deg,
                    var(--color--primary) 0%,
                    var(--color--secondary) 100%
            );
            color: var(--text-color--light);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.5s ease;
            box-shadow: 0 10px 20px rgba(var(--color--primary--alpha), 0.3);
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .tasks-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                    90deg,
                    transparent,
                    rgba(255,255,255,0.2),
                    transparent
            );
            transition: all 0.8s ease;
        }

        .tasks-button:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(var(--color--primary--alpha), 0.5);
        }

        .tasks-button:hover::before {
            left: 100%;
        }

        .tasks-button:active {
            transform: translateY(0) scale(1);
        }

        .in-dev .tasks-button {
            pointer-events: none;
            opacity: 0.7;
            filter: grayscale(70%);
            animation: pulse 2s infinite;
        }

        /* Эффект частиц */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: var(--color--primary);
            border-radius: 50%;
            opacity: 0.3;
            filter: blur(1px);
            animation: floatParticle linear infinite;
        }

        /* Анимации */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes floatParticle {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-100vh) translateX(20px); opacity: 0; }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Адаптация под мобильные */
        @media (max-width: 1280px) {
            .category-single {
                width: 95%;
            }

            .category-title {
                font-size: 2.5rem;
            }

            .category-actions {
                justify-content: center;
            }
        }

        @media (max-width: 840px) {
            .category-page {
                padding: var(--spacing--medium);
            }

            .category-card {
                padding: 25px;
            }

            .category-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 500px) {
            .category-title {
                font-size: 1.8rem;
            }

            .category-description {
                padding: 20px;
            }

            .tasks-button {
                width: 100%;
                justify-content: center;
                padding: 15px;
            }

            .badge {
                margin-left: 10px;
                padding: 5px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
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