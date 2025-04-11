<?php
header("HTTP/1.1 501 Not Implemented");
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ошибка 501 - Не реализовано</title>
    <link rel="stylesheet" href="/global/css/global.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: var(--spacing--large);
            background-image: var(--background-image);
            background-size: cover;
            background-position: center;
        }

        .error-card {
            max-width: 600px;
            width: 100%;
            padding: var(--spacing--large);
            background-color: rgba(var(--background-color--primary--alpha), 0.9);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-04);
            border: 1px solid var(--border-color);
        }

        .error-code {
            font-size: 6rem;
            font-weight: bold;
            margin: 0;
            color: var(--color--error--basic);
            font-family: 'HackerFonts', sans-serif;
        }

        .error-title {
            font-size: 2rem;
            margin: var(--spacing--medium) 0;
            color: var(--text-color--primary);
        }

        .error-message {
            color: var(--text-color--secondary);
            margin-bottom: var(--spacing--large);
            line-height: 1.6;
        }

        .home-button {
            display: inline-block;
            padding: var(--spacing--medium) var(--spacing--large);
            background-color: var(--color--primary);
            color: var(--text-color--light);
            text-decoration: none;
            border-radius: var(--border-radius-button);
            transition: background-color 0.3s ease;
            font-weight: bold;
            box-shadow: var(--shadow-02);
        }

        .home-button:hover {
            background-color: var(--color--primary--hover);
        }

        .error-illustration {
            width: 200px;
            height: 200px;
            margin-bottom: var(--spacing--large);
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="error-container">
    <div class="error-card">
        <svg class="error-illustration" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12 16H12.01" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12 8V12" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 5L19 3" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 19L8 21" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3 5L5 8" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M21 19L19 16" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h1 class="error-code">501</h1>
        <h2 class="error-title">Функционал не реализован</h2>
        <p class="error-message">Запрошенный функционал временно недоступен. Наши разработчики уже работают над его реализацией.</p>
        <div style="margin-top: var(--spacing--medium);">
            <a href="/" class="home-button">На главную</a>
        </div>
    </div>
</body>
</html>