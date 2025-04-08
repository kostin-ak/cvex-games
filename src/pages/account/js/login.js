$(document).ready(function() {
    const $form = $('#login-form');
    const $errorMessage = $('#error-message');
    const $passwordInput = $('#password');
    const $togglePassword = $('#toggle-password');
    const $usernameInput = $('#username');

    // Переключение видимости пароля
    $togglePassword.on('click', function() {
        const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
        $passwordInput.attr('type', type);
        $(this).text(type === 'password' ? 'visibility' : 'visibility_off');
    });

    function toggleValidationStyles(input) {
        if (input.value.length === 0) {
            input.removeAttribute('pattern');
        } else {
            input.setAttribute('pattern', '[a-zA-Z0-9@._-]{3,}');
        }
    }

// И обновите обработчик submit, добавив в начало:
    $form.on('submit', function(e) {
        e.preventDefault();
        // Устанавливаем pattern всем обязательным полям перед отправкой
        $('input:required').each(function() {
            if (!$(this).attr('pattern') && $(this).val().length > 0) {
                $(this).attr('pattern', '.{3,}');
            }
        });
        authenticateUser();
    });

    // Прятать сообщение об ошибке при вводе
    $form.find('input').on('input', function() {
        if ($errorMessage.hasClass('active')) {
            $errorMessage.removeClass('active');
        }
    });

    // Функция аутентификации
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