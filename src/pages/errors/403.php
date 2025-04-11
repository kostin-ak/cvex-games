<?php
header("HTTP/1.1 403 Forbidden");
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ошибка 403 - Доступ запрещен</title>
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
            <path d="M19 11H5C3.89543 11 3 11.8954 3 13V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V13C21 11.8954 20.1046 11 19 11Z" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="var(--color--error--basic)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12 15V18" stroke="var(--color--error--basic)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="12" cy="20" r="1" fill="var(--color--error--basic)"/>
        </svg>
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Доступ запрещен</h2>
        <p class="error-message">У вас нет разрешения на доступ к этой странице. Если вы считаете, что это ошибка, обратитесь к администратору.</p>
        <a href="/" class="home-button">На главную</a>
    </div>
</div>
</body>
</html>