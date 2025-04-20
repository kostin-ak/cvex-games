<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cVEX | Погружение в CTF</title>
    <link rel="stylesheet" href="/global.css">
    <style>
        /* Дополнительные стили, расширяющие global.css */
        :root {
            --terminal-border: 1px solid var(--color--secondary);
            --glow-effect: 0 0 10px rgba(var(--color--primary--alpha), 0.5);
        }

        @keyframes terminalTyping {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes flicker {
            0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
                text-shadow: var(--glow-effect);
            }
            20%, 24%, 55% {
                text-shadow: none;
            }
        }


        .main {
            margin: 0 auto;
            padding: var(--spacing--large);
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .logo1 {
            font-family: 'HackerFonts', var(--font-family);
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: var(--spacing--medium);
            animation: flicker 8s infinite alternate;
            color: var(--color--primary);
            position: relative;
            display: inline-block;
        }

        .logo1::after {
            content: "_";
            animation: blink 1s step-end infinite;
            color: var(--color--primary);
        }

        @keyframes blink {
            from, to { opacity: 0 }
            50% { opacity: 1 }
        }

        .tagline {
            font-size: 1.5rem;
            opacity: 0.8;
            margin-bottom: var(--spacing--large);
            color: var(--color--secondary);
        }

        .hacker-terminal {
            background: rgba(var(--background-color--secondary--alpha), 0.9);
            /*border: var(--terminal-border);*/
            border-radius: calc(var(--border-radius)*3);
            padding: var(--spacing--large);
            margin: var(--spacing--large) 0;
            box-shadow: var(--shadow-02);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .terminal-header {
            display: flex;
            margin-bottom: var(--spacing--medium);
            border-bottom: 1px solid var(--color--secondary);
            padding-bottom: var(--spacing--small);
        }

        .terminal-btn {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .terminal-btn.red { background: var(--color--error--basic); }
        .terminal-btn.yellow { background: var(--color--warning--basic); }
        .terminal-btn.green { background: var(--color--good--basic); }

        .terminal-title {
            margin-left: var(--spacing--small);
            font-size: 0.9rem;
            color: var(--text-color--secondary);
        }

        .command {
            display: flex;
            margin-bottom: var(--spacing--small);
        }

        .prompt {
            color: var(--color--primary);
            margin-right: var(--spacing--small);
        }

        .typing-effect {
            overflow: hidden;
            white-space: nowrap;
            animation: terminalTyping 3s steps(40, end);
            color: var(--text-color--primary);
        }

        .ctf-explanation {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: var(--spacing--large);
            margin: var(--spacing--large) 0;
        }

        .ctf-card {
            background: rgba(var(--background-color--secondary--alpha), 0.7);
            /*border: 1px solid var(--color--secondary);*/
            border-radius: calc(var(--border-radius)*3);
            padding: var(--spacing--large);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .ctf-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-03);
            background: rgba(var(--background-color--secondary--alpha), 0.9);
        }

        .ctf-card h3 {
            color: var(--color--primary);
            margin-bottom: var(--spacing--medium);
            font-size: 1.3rem;
            border-bottom: 1px solid var(--color--secondary);
            padding-bottom: var(--spacing--small);
        }

        .ctf-card ul {
            padding-left: var(--spacing--medium);
            color: var(--text-color--primary);
        }

        .ctf-card li {
            margin-bottom: var(--spacing--small);
        }

        .interactive-demo {
            margin: var(--spacing--large) 0;
            text-align: center;
        }

        .flag-input {
            background: rgba(var(--background-color--secondary--alpha), 0.7);
            border: 1px solid var(--color--secondary);
            color: var(--text-color--primary);
            padding: var(--spacing--medium);
            font-size: 1rem;
            width: 300px;
            margin-right: var(--spacing--medium);
            font-family: 'CodeFonts', monospace;
            border-radius: calc(var(--border-radius)*3);
        }

        .submit-btn {
            background: var(--color--primary);
            color: var(--text-color--light);
            border: none;
            padding: var(--spacing--medium) var(--spacing--large);
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'CodeFonts', monospace;
            border-radius: calc(var(--border-radius)*3);
        }

        .submit-btn:hover {
            background: var(--color--primary--hover);
            box-shadow: var(--shadow-02);
        }

        .result-message {
            margin-top: var(--spacing--medium);
            height: 20px;
            color: var(--text-color--primary);
        }

        .ctf-types {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: var(--spacing--large) 0;
            gap: var(--spacing--medium);
            row-gap: 25px;
        }

        .ctf-type {
            position: relative;
            width: 200px;
            height: 200px;
            perspective: 1000px;
        }

        .ctf-type-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        .ctf-type:hover .ctf-type-inner {
            transform: rotateY(180deg);
        }

        .ctf-type-front, .ctf-type-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            /*border: 1px solid var(--color--secondary);*/
            border-radius: calc(var(--border-radius)*3);
            padding: var(--spacing--medium);
            text-align: center;
        }

        .ctf-type-front {
            background: rgba(var(--background-color--secondary--alpha), 0.7);
            color: var(--text-color--primary);
        }

        .ctf-type-back {
            background: rgba(var(--color--primary--alpha), 0.1);
            transform: rotateY(180deg);
            color: var(--text-color--primary);
        }

        @media (max-width: 768px) {
            .logo1 {
                font-size: 2.5rem;
            }
            .flag-input {
                width: 100%;
                margin-right: 0;
                margin-bottom: var(--spacing--medium);
            }
            .ctf-types {
                flex-direction: column;
                align-items: center;
            }
            .ctf-type {
                width: 100%;
                max-width: 300px;
                margin-bottom: var(--spacing--medium);
            }
        }
    </style>
</head>
<body>

<div class="main">
    <header class="header">
        <div class="logo1">cVEX</div>
        <div class="tagline">> Взломай систему. Найди флаг. Докажи свои навыки.</div>
    </header>

    <div class="hacker-terminal">
        <div class="terminal-header">
            <div class="terminal-btn red"></div>
            <div class="terminal-btn yellow"></div>
            <div class="terminal-btn green"></div>
            <div class="terminal-title">user@cvex:~</div>
        </div>
        <div class="command">
            <span class="prompt">$</span>
            <div class="typing-effect">explain --ctf --full</div>
        </div>
        <div class="command">
            <span class="prompt">></span>
            <div>Capture The Flag (CTF) - это киберспортивные соревнования, где участники применяют навыки взлома и защиты систем для поиска специальных "флагов".</div>
        </div>
        <div class="command">
            <span class="prompt">></span>
            <div>Флаг - это строка вида CVEX{...}, спрятанная в уязвимой системе или полученная в результате эксплуатации уязвимости.</div>
        </div>
    </div>

    <div class="ctf-explanation">
        <div class="ctf-card">
            <h3>Зачем участвовать?</h3>
            <p>CTF - лучший способ получить практический опыт в информационной безопасности. Вы научитесь:</p>
            <ul>
                <li>Находить и эксплуатировать уязвимости</li>
                <li>Анализировать вредоносный код</li>
                <li>Взламывать криптографические системы</li>
                <li>Писать эксплойты</li>
            </ul>
        </div>
        <div class="ctf-card">
            <h3>Как начать?</h3>
            <p>1. Изучите основы:</p>
            <ul>
                <li>Linux и командная строка</li>
                <li>Основы сетевых технологий</li>
                <li>Языки программирования (Python, C)</li>
            </ul>
            <p>2. Практикуйтесь на платформах вроде cVEX!</p>
        </div>
    </div>

    <div class="interactive-demo">
        <h3>Попробуйте найти флаг:</h3>
        <p>Подсказка: флаг начинается с CVEX{ и заканчивается }</p>
        <input type="text" class="flag-input" placeholder="Введите флаг...">
        <button class="submit-btn">Проверить</button>
        <div class="result-message"></div>
    </div>

    <div class="ctf-types">
        <div class="ctf-type">
            <div class="ctf-type-inner">
                <div class="ctf-type-front">
                    <h4>Jeopardy</h4>
                </div>
                <div class="ctf-type-back">
                    <p>Задачи разных категорий с разными баллами. Решайте в любом порядке!</p>
                </div>
            </div>
        </div>
        <div class="ctf-type">
            <div class="ctf-type-inner">
                <div class="ctf-type-front">
                    <h4>Attack-Defense</h4>
                </div>
                <div class="ctf-type-back">
                    <p>Защищайте свои сервисы и атакуйте чужие. Динамичный формат!</p>
                </div>
            </div>
        </div>
        <div class="ctf-type">
            <div class="ctf-type-inner">
                <div class="ctf-type-front">
                    <h4>King of the Hill</h4>
                </div>
                <div class="ctf-type-back">
                    <p>Захватывайте контроль над системой и удерживайте его как можно дольше.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Интерактивная проверка флага
    document.querySelector('.submit-btn').addEventListener('click', function() {
        const input = document.querySelector('.flag-input');
        const result = document.querySelector('.result-message');

        if(input.value === 'CVEX{w3lc0m3_t0_ctf}') {
            result.textContent = '✅ Верно! Флаг принят!';
            result.style.color = 'var(--color--good--basic)';

            // Эффект успеха
            document.querySelector('.interactive-demo').style.boxShadow = '0 0 20px var(--color--good--basic)';
            setTimeout(() => {
                document.querySelector('.interactive-demo').style.boxShadow = 'none';
            }, 1000);
        } else {
            result.textContent = '❌ Неверно! Попробуйте еще раз.';
            result.style.color = 'var(--color--error--basic)';

            // Эффект ошибки
            input.style.borderColor = 'var(--color--error--basic)';
            setTimeout(() => {
                input.style.borderColor = 'var(--color--secondary)';
            }, 1000);
        }
    });

    // Случайные эффекты в терминале
    const terminal = document.querySelector('.hacker-terminal');
    setInterval(() => {
        if(Math.random() > 0.7) {
            terminal.style.boxShadow = `0 0 ${5 + Math.random() * 10}px rgba(var(--color--primary--alpha), 0.5)`;
            setTimeout(() => {
                terminal.style.boxShadow = 'var(--shadow-02)';
            }, 300);
        }
    }, 2000);
</script>
</body>
</html>