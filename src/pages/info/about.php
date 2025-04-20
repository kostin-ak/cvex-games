<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>cVEX | О нас</title>
    <link rel="stylesheet" href="/global.css">
    <style>
        .main {
            width: 100%;
            display: flex;
            flex-direction: column;
            margin: 0 auto;
            padding: var(--spacing--large);
            align-items: center;
            row-gap: 100px;
        }

        header {
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
            text-shadow: var(--shadow-02);
        }

        .tagline {
            font-size: 1.2rem;
            color: var(--text-color--secondary);
        }

        .about-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing--large);
            margin-bottom: 3rem;
        }

        .about-text h2 {
            color: var(--color--primary);
            margin-bottom: var(--spacing--medium);
            font-size: 2rem;
        }

        .about-text p {
            margin-bottom: var(--spacing--medium);
            color: var(--text-color--primary);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing--medium);
            margin-bottom: 3rem;
        }

        .feature-card {
            background: rgba(var(--background-color--secondary--alpha), 0.8);
            padding: var(--spacing--large);
            border-radius: calc(var(--border-radius-button)*3);
            transition: transform 0.3s ease;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-01);
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-02);
            background: rgba(var(--background-color--secondary--alpha), 0.9);
        }

        .feature-card h3 {
            color: var(--color--secondary);
            margin-bottom: var(--spacing--small);
        }

        .feature-card p {
            color: var(--text-color--secondary);
        }

        .cta {
            text-align: center;
            padding: 3rem 0;
        }

        .cta h2 {
            color: var(--color--primary);
            margin-bottom: var(--spacing--medium);
        }

        .cta p {
            color: var(--text-color--secondary);
            margin-bottom: var(--spacing--large);
        }

        @media (max-width: 768px) {
            .about-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="main">
    <header>
        <div class="logo1">cVEX</div>
        <div class="tagline">Платформа для захватывающих CTF-соревнований</div>
    </header>

        <div class="about-text">
            <h2>О платформе cVEX</h2>
            <p>cVEX - это инновационная платформа для проведения Capture The Flag (CTF) соревнований, созданная энтузиастами информационной безопасности для сообщества.</p>
            <p>Наша миссия - предоставить удобную, безопасную и масштабируемую среду для проведения соревнований любого уровня сложности - от локальных мероприятий до международных чемпионатов.</p>
            <p>Платформа разработана с учетом современных требований к кибербезопасности и постоянно обновляется, чтобы соответствовать последним трендам в области CTF.</p>
        </div>

    <section class="features">
        <div class="feature-card">
            <h3>Разнообразие категорий</h3>
            <p>Широкий спектр задач: криптография, веб-уязвимости, реверс-инжиниринг, форензика и многое другое.</p>
        </div>
        <div class="feature-card">
            <h3>Интуитивный интерфейс</h3>
            <p>Удобная панель управления для участников с понятной навигацией и статистикой.</p>
        </div>
        <div class="feature-card">
            <h3>Автоматическая проверка</h3>
            <p>Мгновенная верификация флагов и автоматическое начисление баллов за решенные задачи.</p>
        </div>
        <div class="feature-card">
            <h3>Безопасность</h3>
            <p>Изолированная среда для каждого участника, защита от читерства и DDoS-атак.</p>
        </div>
    </section>

    <section class="cta">
        <h2>Готовы начать своё CTF-приключение?</h2>
        <p>Присоединяйтесь к сообществу cVEX и испытайте свои навыки кибербезопасности!</p>
        <a href="/signup" class="button--primary">Зарегистрироваться</a>
    </section>
</div>
</body>
</html>