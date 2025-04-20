<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cVEX | Условия использования</title>
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
        <div class="tagline">Условия использования платформы</div>
    </header>

    <div class="section">
        <h2>1. Принятие условий</h2>
        <p>Используя платформу cVEX (далее — "Платформа"), вы соглашаетесь с настоящими Условиями. Если вы не согласны — прекратите использование.</p>
        <p>На некоторые сервисы могут распространяться дополнительные условия (например, <span class="highlight">terms</span> при вводе флагов).</p>
    </div>

    <div class="section">
        <h2>2. Обязанности пользователей</h2>
        <h3>2.1 Правила использования</h3>
        <p>Запрещено:</p>
        <ul>
            <li>Участвовать в несанкционированном доступе или взломе</li>
            <li>Использовать эксплойты для влияния на работу Платформы</li>
            <li>Публиковать вредоносный код через формы ввода</li>
            <li>Нарушать законы РФ и международные законы</li>
        </ul>

        <h3>2.2 Ответственность</h3>
        <p>Вы несете ответственность за:</p>
        <ul>
            <li>Соответствие ваших действий настоящим Условиям</li>
            <li>Безопасность своих учетных данных</li>
            <li>Контент, который вы публикуете на Платформе</li>
        </ul>
    </div>

    <div class="section">
        <h2>3. Интеллектуальная собственность</h2>
        <p>Все элементы Платформы, включая:</p>
        <ul>
            <li>Исходный код платформы</li>
            <li>Дизайн, логотипы и название (<span class="highlight">cVEX</span> в шапке)</li>
            <li>Контент заданий и флаги (например, <span class="highlight">CVEX{...}</span>)</li>
        </ul>
        <p>являются нашей собственностью и защищены авторским правом.</p>
    </div>

    <div class="section">
        <h2>4. Ограничения гарантий</h2>
        <ul>
            <li>Платформа предоставляется "как есть" — мы не гарантируем бесперебойную работу</li>
            <li>Мы не несем ответственность за ущерб от использования Платформы</li>
            <li>Технические работы могут проводиться без предупреждения</li>
        </ul>
    </div>

    <div class="section">
        <h2>5. Изменения условий</h2>
        <p>Мы можем изменять эти Условия. Об изменениях уведомим:</p>
        <ul>
            <li>Через платформу (баннером или на специально отведенной странице)</li>
            <li>По email (если вы его указали)</li>
        </ul>
        <p>Продолжение использования после изменений означает ваше согласие.</p>
    </div>

    <div class="section">
        <h2>6. Прекращение доступа</h2>
        <p>Мы можем ограничить или прекратить ваш доступ если:</p>
        <ul>
            <li>Вы нарушаете эти Условия</li>
            <li>Ваши действия угрожают безопасности Платформы</li>
            <li>Это требуется по закону</li>
        </ul>
    </div>

    <div class="section">
        <h2>7. Разрешение споров</h2>
        <p>Все споры решаются путем переговоров. Если соглашение не достигнуто — спор передается в суд по месту нашей регистрации.</p>
        <p>Для связи используйте: <a href="mailto:kostin.ak@mail.ru" class="contact-link">kostin.ak@mail.ru</a> или Telegram: <span class="highlight">@cvex_ctf_bot</span>.</p>
    </div>
</div>
</body>
</html>