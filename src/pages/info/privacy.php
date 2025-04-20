<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cVEX | Политика конфиденциальности</title>
    <link rel="stylesheet" href="/global.css">
    <style>
        .main {
            margin: 150px auto !important;
            max-width: 1200px;
            padding: calc(var(--spacing--large)*2);
            color: var(--text-color--primary);
            background-color: rgba(var(--background-color--secondary--alpha), 0.8);
            backdrop-filter: blur(10px);
            border-radius: 25px;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: var(--spacing--medium);
            border-bottom: 2px solid var(--color--primary);
        }

        .logo1 {
            font-family: 'HackerFonts', var(--font-family);
            font-size: 3rem;
            font-weight: 800;
            color: var(--color--primary);
            margin-bottom: var(--spacing--medium);
        }

        .tagline {
            font-size: 1.2rem;
            color: var(--text-color--secondary);
        }

        .section {
            margin-bottom: 3rem;
        }

        h2 {
            color: var(--color--primary);
            margin-bottom: var(--spacing--medium);
            border-left: 4px solid var(--color--secondary);
            padding-left: var(--spacing--medium);
        }

        h3 {
            color: var(--color--secondary);
            margin: var(--spacing--medium) 0;
        }

        p, ul {
            margin-bottom: var(--spacing--medium);
            line-height: 1.6;
        }

        ul {
            padding-left: var(--spacing--large);
        }

        li {
            margin-bottom: var(--spacing--small);
        }

        .highlight {
            background: rgba(var(--color--primary--alpha), 0.1);
            padding: var(--spacing--small) var(--spacing--medium);
            border-radius: var(--border-radius);
            font-family: 'CodeFonts', monospace;
            display: inline-block;
        }

        .contact-link {
            color: var(--color--secondary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .contact-link:hover {
            color: var(--color--primary);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main {
                padding: var(--spacing--medium);
            }
            .logo1 {
                font-size: 2rem;
            }
        }

        .section li a{
            text-decoration-color: var(--text-color--secondary);
            color: var(--text-color--secondary);
        }
    </style>
</head>
<body>
<div class="main">
    <header class="header">
        <div class="logo1">cVEX</div>
        <div class="tagline">Политика конфиденциальности и защиты данных</div>
    </header>

    <div class="section">
        <h2>1. Введение</h2>
        <p>Платформа cVEX (далее — "Мы") уважает вашу конфиденциальность. Настоящая Политика объясняет, как мы собираем, используем и защищаем ваши данные при использовании нашего сайта и сервисов.</p>
        <p>Используя cVEX, вы соглашаетесь с условиями этой Политики.</p>
    </div>

    <div class="section">
        <h2>2. Собираемые данные</h2>
        <h3>2.1 Персональные данные</h3>
        <p>Мы можем запросить или автоматически получить:</p>
        <ul>
            <li>Имя и контактные данные при регистрации или обращении в поддержку</li>
            <li>Данные формы ввода флагов (например, <span class="highlight">CVEX{w3lc0m3_t0_ctf}</span>)</li>
            <li>Команды, введенные в интерактивные терминалы (см. <span class="highlight">help</span> в <span class="highlight"><a href="/contacts">contacts</a></span>)</li>
        </ul>

        <h3>2.2 Технические данные</h3>
        <p>Автоматически собираются:</p>
        <ul>
            <li>IP-адрес, тип браузера, версия ОС</li>
            <li>Данные cookie (см. стили в <span class="highlight">global.css</span>)</li>
            <li>Журналы взаимодействия с сервером</li>
        </ul>
    </div>

    <div class="section">
        <h2>3. Использование данных</h2>
        <p>Ваши данные используются для:</p>
        <ul>
            <li>Обеспечения работы платформы (формы, терминалы, проверка флагов)</li>
            <li>Улучшения пользовательского опыта (анализ поведения)</li>
            <li>Обратной связи (через указанные контакты, например <span class="highlight">support@cvex.ctf</span>)</li>
            <li>Защиты от мошенничества и нарушений</li>
        </ul>
    </div>

    <div class="section">
        <h2>4. Защита данных</h2>
        <p>Мы применяем меры безопасности, включая:</p>
        <ul>
            <li>Шифрование передаваемых данных (HTTPS)</li>
            <li>Регулярные аудиты безопасности</li>
            <li>Ограниченный доступ к персональным данным</li>
            <li>Резервное копирование и защиту серверов</li>
        </ul>
        <p>Однако абсолютная безопасность в интернете невозможна — используйте сложные пароли и не сообщайте данные третьим лицам.</p>
    </div>

    <div class="section">
        <h2>5. Ваши права</h2>
        <p>Вы можете:</p>
        <ul>
            <li>Запросить доступ к вашим данным</li>
            <li>Потребовать исправления или удаления данных</li>
            <li>Отказаться от рассылок (если они есть)</li>
            <li>Подать жалобу в регуляторный орган</li>
        </ul>
        <p>Для реализации прав обратитесь на <a href="mailto:kostin.ak@mail.ru" class="contact-link">kostin.ak@mail.ru</a>.</p>
    </div>

    <div class="section">
        <h2>6. Изменения в Политике</h2>
        <p>Мы можем обновлять эту Политику. Актуальная версия всегда доступна на этой странице. При значительных изменениях уведомим пользователей через платформу или email.</p>
    </div>

    <div class="section">
        <h2>7. Контакты</h2>
        <p>По вопросам конфиденциальности обращайтесь:</p>
        <p>Email: <a href="mailto:kostin.ak@mail.ru" class="contact-link">kostin.ak@mail.ru</a></p>
        <p>Телеграм: <span class="highlight">@cvex_ctf_bot</span> (подтверждено в contacts.php)</p>
    </div>
</div>
</body>
</html>