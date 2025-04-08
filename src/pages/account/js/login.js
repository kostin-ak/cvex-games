$(document).ready(function() {
    const $form = $('#login-form');
    const $errorMessage = $('#error-message');
    const $passwordInput = $('#password');
    const $togglePassword = $('#toggle-password');
    const $usernameInput = $('#username');
    const $usernameTooltip = $('#username-tooltip');
    const $passwordTooltip = $('#password-tooltip');


    // Убираем обработчик input (если он был)
    $passwordInput.off('input');
    $usernameInput.off('input');

    // Валидация при изменении поля (но не скрываем подсказку сразу)
    $passwordInput.on('input', function() {
        validateField($(this), $passwordTooltip, 'Минимум 4 символа');
        // Не скрываем подсказку здесь, только обновляем текст если нужно
    });

    $usernameInput.on('input', function() {
        validateField($(this), $usernameTooltip, 'Минимум 3 символа (буквы, цифры, @ . _ -)');
        // Не скрываем подсказку здесь, только обновляем текст если нужно
    });

    // Показываем подсказку при фокусе, только если поле невалидно
    $form.find('input').on('focus', function() {
        if (!this.validity.valid) {
            const $tooltip = $(`#${this.id}-tooltip`);
            const defaultMessage = $tooltip.data('default') ||
                (this.id === 'password' ? 'Минимум 4 символа' : 'Минимум 3 символа (буквы, цифры, @ . _ -)');
            validateField($(this), $tooltip, defaultMessage);
        }
    });

    // Скрываем подсказку только при blur, если поле валидно
    $form.find('input').on('blur', function() {
        const $tooltip = $(`#${this.id}-tooltip`);
        if (this.validity.valid) {
            $tooltip.removeClass('active');
        } else {
            const defaultMessage = $tooltip.data('default') ||
                (this.id === 'password' ? 'Минимум 4 символа' : 'Минимум 3 символа (буквы, цифры, @ . _ -)');
            validateField($(this), $tooltip, defaultMessage);
        }
    });

    // Переключение видимости пароля
    $togglePassword.on('click', function() {
        const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
        $passwordInput.attr('type', type);
        $(this).text(type === 'password' ? 'visibility' : 'visibility_off');
    });

    // Валидация при вводе
    $passwordInput.on('input', function() {
        validateField($(this), $passwordTooltip, 'Минимум 4 символа');
    });

    $usernameInput.on('input', function() {
        validateField($(this), $usernameTooltip, 'Минимум 3 символа (буквы, цифры, @ . _ -)');
    });

    // Функция валидации поля
    function validateField($input, $tooltip, defaultMessage) {
        if ($input[0].validity.valid) {
            // Поле валидно - скрываем подсказку
            $tooltip.removeClass('active');
        } else {
            // Поле невалидно - показываем соответствующее сообщение
            let message = defaultMessage;

            if ($input[0].validity.valueMissing) {
                message = 'Это поле обязательно для заполнения';
            } else if ($input[0].validity.tooShort) {
                message = `Минимум ${$input.attr('minlength')} символа`;
            } else if ($input[0].validity.patternMismatch) {
                message = 'Недопустимые символы в поле';
            }

            $tooltip.text(message).addClass('active');
        }
    }

    $form.on('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        // Проверяем каждое поле
        $form.find('input[required]').each(function() {
            const $input = $(this);
            const $tooltip = $(`#${this.id}-tooltip`);

            if (!$input[0].validity.valid) {
                validateField($input, $tooltip, $tooltip.data('default') || 'Некорректное значение');
                isValid = false;
                $input.focus();
                return false; // Прерываем цикл при первой ошибке
            }
        });

        if (!isValid) return;

        authenticateUser();
    });

    // Прятать подсказки при фокусе
    $form.find('input').on('focus', function() {
        $(`#${this.id}-tooltip`).removeClass('active');
    });

    // Показывать подсказки при blur, если поле невалидно
    $form.find('input').on('blur', function() {
        if (!this.validity.valid) {
            const $tooltip = $(`#${this.id}-tooltip`);
            validateField($(this), $tooltip, $tooltip.data('default') || 'Некорректное значение');
        }
    });

    // Функция аутентификации остается без изменений
        function authenticateUser() {
            const data = {
                login: $usernameInput.val().trim(),
                password: $passwordInput.val(),
                link: $('#link').val()
            };

            console.log(data);

            // Валидация на клиенте
            if (!data.login || !data.password) {
                showError('Пожалуйста, заполните все поля');
                return;
            }

            if (data.login.length < 3) {
                showError('Логин должен содержать минимум 3 символа');
                return;
            }

            if (data.password.length < 4) {
                showError('Пароль должен содержать минимум 4 символов');
                return;
            }

            // Показать лоадер
            $form.find('button[type="submit"]').prop('disabled', true)
                .html('<span class="material-icons">hourglass_top</span> Загрузка...');

            $.ajax({
                url: '/server/auth.php',
                type: 'POST',
                data: data,
                //dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        window.location.href = data.link;
                    } else {
                        showError(response.message || 'Неверный логин или пароль');
                    }
                },
                error: function(xhr) {
                    showError(xhr.responseJSON?.message || 'Произошла ошибка. Попробуйте позже.');
                },
                complete: function() {
                    $form.find('button[type="submit"]').prop('disabled', false).text('Войти');
                }
            });
        }

    function showError(message) {
        $errorMessage.text(message).addClass('active');
        $passwordInput.val('').focus();
    }
});