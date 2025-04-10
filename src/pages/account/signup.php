<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Добавьте стили из примера и следующие дополнительные стилы */

        .main{
            width: 100%;
            display: flex;
            justify-content: center;
        }


        .password-strength {
            margin-top: 5px;
            height: 3px;
            background: var(--background-color--primary);
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength span {
            display: block;
            height: 100%;
            transition: width 0.3s ease;
        }

        .strength-weak { background: #ff4757; width: 33%; }
        .strength-medium { background: #ffa502; width: 66%; }
        .strength-strong { background: #2ed573; width: 100%; }

        .group-select {
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            border: 1px solid var(--border-color);
            background: var(--background-color--primary);
            color: var(--text-color--secondary);
            transition: all 0.3s ease;
        }

        .group-select:focus {
            outline: none;
            border-color: var(--color--primary);
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.2);
        }
        .password-requirements {
            margin-top: 10px;
            padding: 10px;
            background: rgba(var(--background-color--primary--alpha), 0.1);
            border-radius: var(--border-radius);
            font-size: 0.9em;
        }

        .password-requirements ul {
            margin: 5px 0 0 15px;
            padding: 0;
            list-style: none;
        }

        .password-requirements li {
            position: relative;
            margin-bottom: 3px;
            color: var(--text-color--secondary);
            transition: color 0.3s ease;
        }

        .password-requirements li::before {
            content: '✖';
            color: #ff4757;
            margin-right: 8px;
        }

        .password-requirements li.valid {
            color: var(--text-color--primary);
        }

        .password-requirements li.valid::before {
            content: '✓';
            color: #2ed573;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="login-card">
        <div class="error-message" id="error-message"></div>
        <h1 class="login-header">Регистрация</h1>

        <form id="register-form" novalidate>
            <div class="field-group">
                <label for="username" class="field-label">Имя пользователя</label>
                <input type="text" id="username" name="username" class="field-input"
                       placeholder="Введите ваш никнейм" required minlength="3"
                       pattern="^[a-zA-Z0-9@._-]{3,}$">
                <div class="tooltip" id="username-tooltip">Минимум 3 символа (буквы, цифры, @ . _ -)</div>
            </div>

            <div class="field-group">
                <label for="email" class="field-label">Email</label>
                <input type="email" id="email" name="email" class="field-input"
                       placeholder="Введите ваш email" required>
                <div class="tooltip" id="email-tooltip">Введите корректный email адрес</div>
            </div>

            <div class="field-group">
                <label for="name" class="field-label">Имя</label>
                <input type="text" id="name" name="name" class="field-input"
                       placeholder="Введите ваше имя" required>
                <div class="tooltip" id="name-tooltip">Это поле обязательно для заполнения</div>
            </div>

            <div class="field-group">
                <label for="sname" class="field-label">Фамилия</label>
                <input type="text" id="sname" name="sname" class="field-input"
                       placeholder="Введите вашу фамилию" required>
                <div class="tooltip" id="sname-tooltip">Это поле обязательно для заполнения</div>
            </div>

            <div class="field-group">
                <label for="password" class="field-label">Пароль</label>
                <input type="password" id="password" name="password" class="field-input"
                       placeholder="Введите пароль" required minlength="8"
                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$">
                <div class="password-strength" style="display: none;"><span id="password-strength-bar"></span></div>
                <div class="password-requirements" style="display: none;">
                    Пароль должен содержать:
                    <ul>
                        <li id="req-length">Минимум 8 символов</li>
                        <li id="req-lowercase">Строчную букву</li>
                        <li id="req-uppercase">Заглавную букву</li>
                        <li id="req-digit">Цифру</li>
                        <li id="req-special">Спецсимвол (!@#$%^&*)</li>
                    </ul>
                </div>
                <div class="tooltip" id="password-tooltip">Исправьте отмеченные требования к паролю</div>
            </div>

            <div class="field-group">
                <label for="confirm-password" class="field-label">Подтвердите пароль</label>
                <input type="password" id="confirm-password" class="field-input"
                       placeholder="Повторите пароль" required>
                <div class="tooltip" id="confirm-password-tooltip">Пароли должны совпадать</div>
            </div>

            <div class="field-group">
                <label for="group" class="field-label">Группа</label>
                <select id="group" name="group" class="group-select" required>
                    <option value="">Выберите группу</option>
                    <option value="1">Школьник</option>
                    <option value="2">Студент</option>
                    <option value="3">Профессионал</option>
                </select>
                <div class="tooltip" id="group-tooltip">Выберите вашу группу</div>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        const $form = $('#register-form');
        const $errorMessage = $('#error-message');
        let usernameAvailable = false;
        let emailAvailable = false;

        // Валидация имени пользователя с проверкой на сервере
        $('#username').on('input', function() {
            const username = $(this).val().trim();
            validateField($(this), $('#username-tooltip'), 'Минимум 3 символа (буквы, цифры, @ . _ -)');

            if(username.length >= 3) {
                checkAvailability('username', username, $('#username-tooltip'));
            }
        });

        // Валидация email с проверкой на сервере
        $('#email').on('input', function() {
            validateField($(this), $('#email-tooltip'), 'Введите корректный email адрес');
            if(validateEmail($(this).val())) {
                checkAvailability('email', $(this).val(), $('#email-tooltip'));
            }
        });

        $('#password').on('input', function() {
            const password = $(this).val();

            // Показываем проверку пароля только если начали ввод
            if(password.length > 0) {
                $('.password-strength, .password-requirements').show();
            } else {
                $('.password-strength, .password-requirements').hide();
            }

            validatePasswordStrength(password);
            validateField($(this), $('#password-tooltip'), 'Минимум 8 символов, хотя бы одна буква и цифра');
            validatePasswordMatch();
        });

        // Валидация подтверждения пароля
        $('#confirm-password').on('input', validatePasswordMatch);


        // Валидация силы пароля
        function validatePasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                digit: /\d/.test(password),
                special: /[!@#$%^&*]/.test(password)
            };

            // Обновляем визуальные индикаторы
            Object.keys(requirements).forEach(req => {
                const element = $(`#req-${req}`);
                element.toggleClass('valid', requirements[req]);
            });

            // Рассчитываем силу пароля
            const strengthLevel = Object.values(requirements).filter(Boolean).length;
            const $bar = $('#password-strength-bar');
            $bar.removeClass('strength-weak strength-medium strength-strong');

            if(strengthLevel === 5) {
                $bar.addClass('strength-strong');
            } else if(strengthLevel >= 3) {
                $bar.addClass('strength-medium');
            } else {
                $bar.addClass('strength-weak');
            }
        }



        // Общая валидация поля с автоматическим исчезновением tooltip
        function validateField($input, $tooltip, defaultMessage) {
            if($input[0].validity.valid) {
                hideTooltip($tooltip);
            } else {
                let message = defaultMessage;
                if($input[0].validity.valueMissing) message = 'Это поле обязательно';
                else if($input[0].validity.patternMismatch) message = 'Некорректный формат';
                showTooltip($tooltip, message);
            }
        }

// Функция для показа tooltip с таймером на скрытие
        function showTooltip($tooltip, message) {
            $tooltip.text(message).addClass('active');

            // Очищаем предыдущий таймер, если он есть
            if($tooltip.data('hideTimeout')) {
                clearTimeout($tooltip.data('hideTimeout'));
            }

            // Устанавливаем новый таймер на скрытие через 5 секунд
            $tooltip.data('hideTimeout', setTimeout(() => {
                hideTooltip($tooltip);
            }, 5000));
        }

// Функция для скрытия tooltip и очистки таймера
        function hideTooltip($tooltip) {
            $tooltip.removeClass('active');
            clearTimeout($tooltip.data('hideTimeout'));
        }

// Проверка совпадения паролей с использованием новых функций
        function validatePasswordMatch() {
            const password = $('#password').val();
            const confirmPassword = $('#confirm-password').val();
            const $tooltip = $('#confirm-password-tooltip');

            if(confirmPassword && password !== confirmPassword) {
                showTooltip($tooltip, 'Пароли должны совпадать');
                return false;
            } else {
                hideTooltip($tooltip);
                return true;
            }
        }

// Модифицируем проверку доступности данных
        function checkAvailability(field, value, $tooltip) {
            $.ajax({
                url: `/server/checker.php?check-${field}`,
                method: 'POST',
                data: { [field]: value },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    console.log(response.available);
                    if(response.available) {
                        hideTooltip($tooltip);
                        if(field === 'username') usernameAvailable = true;
                        if(field === 'email') emailAvailable = true;
                    } else {
                        showTooltip($tooltip, `${field === 'username' ? 'Имя пользователя' : 'Email'} уже занято`);
                        if(field === 'username') usernameAvailable = false;
                        if(field === 'email') emailAvailable = false;
                    }
                },
                error: function (response){
                    console.log(response);
                }
            });
        }

        // Отправка формы
        $form.on('submit', function(e) {
            e.preventDefault();
            let isValid = true;

            // Проверка всех полей
            $form.find('input, select').each(function() {
                const $input = $(this);
                const $tooltip = $(`#${this.id}-tooltip`);
                validateField($input, $tooltip, $tooltip.data('default'));
                if(!$input[0].validity.valid) isValid = false;
            });

            if(!validatePasswordMatch()) isValid = false;
            if(!usernameAvailable || !emailAvailable) isValid = false;

            if(!isValid) {
                $errorMessage.text('Исправьте ошибки в форме').addClass('active');
                return;
            }

            // Сбор данных
            const formData = {
                username: $('#username').val(),
                mail: $('#email').val(),
                name: $('#name').val(),
                sname: $('#sname').val(),
                password: $('#password').val(),
                group: $('#group').val(),
                registered: Date.now()
            };

            // Отправка на сервер
            $.ajax({
                url: '/server/registration.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if(response.success) {
                        window.location.href = '/login';
                    } else {
                        $errorMessage.text(response.message).addClass('active');
                    }
                },
                error: function(error) {
                    console.log(error);
                    $errorMessage.text('Ошибка сервера. Попробуйте позже').addClass('active');
                }
            });
        });

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    });
</script>
</body>
</html>