<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cVEX | Контакты</title>
    <link rel="stylesheet" href="/global.css">
    <style>
        /* Дополнительные стили */
        :root {
            --terminal-border: 1px solid var(--color--secondary);
            --glow-effect: 0 0 10px rgba(var(--color--primary--alpha), 0.5);
            --success-glow: 0 0 15px rgba(var(--background--good--basic--alpha), 0.7);
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

        /* Стили для интерактивного терминала */
        .contact-terminal {
            background: rgba(var(--background-color--secondary--alpha), 0.9);
            /*border: var(--terminal-border);*/
            border-radius: calc(var(--border-radius)*3);
            padding: var(--spacing--large);
            margin: var(--spacing--large) 0;
            box-shadow: var(--shadow-03);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            min-height: 400px;
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

        .command-line {
            display: flex;
            margin-bottom: var(--spacing--small);
        }

        .prompt {
            color: var(--color--primary);
            margin-right: var(--spacing--small);
            font-family: 'CodeFonts', monospace;
        }

        .command-input {
            background: transparent;
            border: none;
            color: var(--text-color--primary);
            font-family: 'CodeFonts', monospace;
            width: calc(100% - 30px);
            outline: none;
            caret-color: var(--color--primary);
        }

        .output {
            margin-bottom: var(--spacing--medium);
            color: var(--text-color--primary);
            font-family: 'CodeFonts', monospace;
            white-space: pre-wrap;
        }

        .success-output {
            color: var(--color--good--basic);
            text-shadow: var(--glow-effect);
        }

        .error-output {
            color: var(--color--error--basic);
        }

        /* Стили для контактных карточек */
        .contact-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing--large);
            margin-top: var(--spacing--large);
        }

        .contact-card {
            background: rgba(var(--background-color--secondary--alpha), 0.7);
            /*border: 1px solid var(--color--secondary);*/
            border-radius: calc(var(--border-radius)*3);
            padding: var(--spacing--large);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-03);
            border-color: var(--color--primary);
        }

        .contact-card h3 {
            color: var(--color--primary);
            margin-bottom: var(--spacing--medium);
            font-size: 1.3rem;
            border-bottom: 1px solid var(--color--secondary);
            padding-bottom: var(--spacing--small);
        }

        .contact-card p {
            margin-bottom: var(--spacing--small);
        }

        .contact-link {
            color: var(--color--secondary);
            text-decoration: none;
            transition: color 0.3s;
            display: inline-block;
            margin-top: var(--spacing--small);
        }

        .contact-link:hover {
            color: var(--color--primary);
            text-decoration: underline;
        }

        /* Стили для скрытого контакта */
        .hidden-contact {
            display: none;
            margin-top: var(--spacing--large);
            padding: var(--spacing--medium);
            background: rgba(var(--color--primary--alpha), 0.1);
            border-left: 3px solid var(--color--primary);
            border-radius: 0 calc(var(--border-radius)*3) calc(var(--border-radius)*3) 0;
        }


        /* Адаптивность */
        @media (max-width: 768px) {
            .logo1 {
                font-size: 2.5rem;
            }
            .contact-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="main">
    <header class="header">
        <div class="logo1">cVEX_CONTACTS</div>
        <div class="tagline">> Свяжитесь с нами через интерактивную консоль</div>
    </header>

    <div class="contact-terminal" id="terminal">
        <div class="terminal-header">
            <div class="terminal-btn red"></div>
            <div class="terminal-btn yellow"></div>
            <div class="terminal-btn green"></div>
            <div class="terminal-title">user@cvex-contacts:~</div>
        </div>

        <div class="output">
            Добро пожаловать в консоль связи cVEX. Доступные команды:
            - <span class="prompt">help</span> - показать список команд
            - <span class="prompt">contacts</span> - показать контакты
            - <span class="prompt">secret</span> - [REDACTED]
            - <span class="prompt">clear</span> - очистить консоль
        </div>

        <div id="terminal-output"></div>

        <div class="command-line">
            <span class="prompt">$</span>
            <input type="text" class="command-input" id="command-input" autofocus>
        </div>
    </div>

    <div class="contact-cards" id="contact-cards" style="display: none;">
        <div class="contact-card">
            <h3>Техническая поддержка</h3>
            <p>По вопросам работы платформы и техническим проблемам</p>
            <a href="mailto:kostin.ak@mail.ru" class="contact-link">support@cvex.ctf</a>
        </div>
        <div class="contact-card">
            <h3>Организация мероприятий</h3>
            <p>Для проведения CTF-соревнований и сотрудничества</p>
            <a href="mailto:kostin.ak@mail.ru" class="contact-link">events@cvex.ctf</a>
        </div>
    </div>

    <div class="hidden-contact" id="hidden-contact">
        <h3>Секретный контакт</h3>
        <p>Только для избранных. Если вы нашли этот контакт через консоль, вы достойны:</p>
        <p class="success-output">CTF-мастер: <span id="secret-email">[загружается...]</span></p>
        <p>Отправьте письмо с содержанием "CVEX{terminal_wizard}"</p>
    </div>
</div>

<script>
    // Имитация терминала
    const terminalInput = document.getElementById('command-input');
    const terminalOutput = document.getElementById('terminal-output');
    const contactCards = document.getElementById('contact-cards');
    const hiddenContact = document.getElementById('hidden-contact');

    // Секретный контакт (загружается через JS для защиты от спама)
    document.getElementById('secret-email').textContent = ['t', 'me'].join('.') + '/' + ['cvex', 'ctf', 'bot'].join('_');

    const commands = {
        help: () => `<div class="output">
Доступные команды:
- <span class="prompt">help</span> - показать список команд
- <span class="prompt">contacts</span> - показать контакты
- <span class="prompt">secret</span> - [REDACTED]
- <span class="prompt">clear</span> - очистить консоль
- <span class="prompt">cv3x_unlock</span> - показать секретный контакт
            </div>`,

        contacts: () => {
            contactCards.style.display = 'grid';
            return `<div class="output success-output">
Контакты загружены. Прокрутите ниже чтобы увидеть.
                </div>`;
        },

        secret: () => `<div class="output error-output">
Ошибка: недостаточно прав. Попробуйте найти секретную команду.
            </div>`,

        clear: () => {
            terminalOutput.innerHTML = '';
            return '';
        },

        cv3x_unlock: () => {
            hiddenContact.style.display = 'block';
            return `<div class="output success-output">
Доступ разрешен. Секретный контакт разблокирован!
                </div>`;
        },

        default: (cmd) => `<div class="output error-output">
Команда "${cmd}" не найдена. Введите <span class="prompt">help</span> для списка команд.
            </div>`
    };

    terminalInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const command = terminalInput.value.trim().toLowerCase();
            terminalInput.value = '';

            let output;
            if (commands[command]) {
                output = commands[command]();
            } else {
                output = commands.default(command);
            }

            terminalOutput.innerHTML += `
                    <div class="command-line">
                        <span class="prompt">$</span>
                        <span>${command}</span>
                    </div>
                    ${output}
                `;

            // Автопрокрутка вниз
            terminalOutput.scrollTop = terminalOutput.scrollHeight;
        }
    });

    // Случайные эффекты в терминале
    const terminal = document.querySelector('.contact-terminal');
    setInterval(() => {
        if(Math.random() > 0.7) {
            terminal.style.boxShadow = `0 0 ${5 + Math.random() * 10}px rgba(var(--color--primary--alpha), 0.5)`;
            setTimeout(() => {
                terminal.style.boxShadow = 'var(--shadow-03)';
            }, 300);
        }
    }, 2000);
</script>
</body>
</html>