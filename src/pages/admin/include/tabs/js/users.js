$(document).ready(function() {
    const roleMap = {0: 'Пользователь', 1: 'Админ', 2: 'Модератор'};
    const groupMap = {0: 'все', 1: 'школьник', 2: 'студент', 3: 'профи'};

    $('.modal').html("<div class=\"modal-overlay\" id=\"editUserModal\">\n" +
        "        <div class=\"modal-container\">\n" +
        "            <div class=\"modal-header\">\n" +
        "                <h3>Редактировать пользователя</h3>\n" +
        "                <button class=\"modal-close-btn\">×</button>\n" +
        "            </div>\n" +
        "            <div class=\"modal-body\">\n" +
        "                <form id=\"editUserForm\">\n" +
        "                    <input type=\"hidden\" id=\"editUserId\">\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editName\">Имя</label>\n" +
        "                        <input type=\"text\" id=\"editName\" required>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editSName\">Фамилия</label>\n" +
        "                        <input type=\"text\" id=\"editSName\" required>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editUsername\">Логин</label>\n" +
        "                        <input type=\"text\" id=\"editUsername\" required>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editEmail\">Email</label>\n" +
        "                        <input type=\"email\" id=\"editEmail\" required>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editRole\">Роль</label>\n" +
        "                        <select id=\"editRole\" required>\n" +
        "                            <option value=\"0\">Пользователь</option>\n" +
        "                            <option value=\"1\">Админ</option>\n" +
        "                            <option value=\"2\">Модератор</option>\n" +
        "                        </select>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editGroup\">Группа</label>\n" +
        "                        <select id=\"editGroup\" required>\n" +
        "                            <option value=\"0\">Все</option>\n" +
        "                            <option value=\"1\">Школьник</option>\n" +
        "                            <option value=\"2\">Студент</option>\n" +
        "                            <option value=\"3\">Профи</option>\n" +
        "                        </select>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-group\">\n" +
        "                        <label for=\"editScore\">Очки</label>\n" +
        "                        <input type=\"number\" id=\"editScore\" required>\n" +
        "                    </div>\n" +
        "\n" +
        "                    <div class=\"form-actions\">\n" +
        "                        <button type=\"button\" class=\"btn-cancel\">Отмена</button>\n" +
        "                        <button type=\"submit\" class=\"btn-save\">Сохранить</button>\n" +
        "                    </div>\n" +
        "                </form>\n" +
        "            </div>\n" +
        "        </div>\n" +
        "    </div>");

    // Инициализация
    loadUsers();
    setupEventListeners();

    function loadUsers(filterData = null) {
        showLoadingIndicator();

        $.get('/server/admin/users.php', filterData)
            .done(handleUsersLoadSuccess)
            .fail(handleUsersLoadError);
    }

    function handleUsersLoadSuccess(response) {
        if (response?.data?.users) {
            renderUsers(response.data.users);
        } else {
            showMessage('Ошибка', 'Неверный формат данных', 'error');
        }
    }

    function handleUsersLoadError(error) {
        const errorMessage = error.responseJSON?.error || "Ошибка подключения к серверу";
        showMessage('Ошибка', errorMessage, 'error');
    }

    function renderUsers(users) {
        const $container = $('.users-list');
        if (!$container.length) return;

        $container.html(users.map(createUserCard).join(''));
        setupEditButtons();
    }

    function createUserCard(user) {
        return `
            <div class="user-card" data-user-id="${user.uuid}">
                <div class="user-header">
                    <h2>${user.name} ${user.sname}</h2>
                    <div class="user-actions">
                        <span class="role role-${user.role}">${roleMap[user.role] || 'Unknown'}</span>
                        <button class="edit-user-btn" title="Редактировать пользователя">
                            <span class="material-icons">settings</span>
                        </button>
                    </div>
                </div>
                <div class="user-details">
                    <div class="detail"><i class="fas fa-user"></i> #${user.username}</div>
                    <div class="detail"><i class="fas fa-envelope"></i> ${user.mail}</div>
                    <div class="detail"><i class="fas fa-star"></i> Очки: ${user.score}</div>
                    <div class="detail"><i class="fas fa-users"></i> Группа: ${groupMap[user.group] || 'Unknown'}</div>
                    <div class="detail"><i class="fas fa-calendar-alt"></i> ${new Date(user.registered).toLocaleString()}</div>
                </div>
            </div>
        `;
    }

    function setupEditButtons() {
        $('.users-list').on('click', '.edit-user-btn', function() {
            const userId = $(this).closest('.user-card').data('userId');
            editUser(userId);
        });
    }

    function editUser(userId) {
        showLoadingIndicator();

        $.get(`/server/admin/users.php?uuid=${userId}`)
            .done(function(response) {
                if (response.success) {
                    fillEditForm(response.data);
                    openModal();
                } else {
                    showMessage('Ошибка', 'Не удалось загрузить данные пользователя', 'error');
                }
            })
            .fail(handleUsersLoadError);
    }

    function fillEditForm(userData) {
        const $form = $('#editUserForm');
        $form.find('#editUserId').val(userData.uuid);
        $form.find('#editName').val(userData.name);
        $form.find('#editSName').val(userData.sname);
        $form.find('#editUsername').val(userData.username);
        $form.find('#editEmail').val(userData.mail);
        $form.find('#editRole').val(userData.role);
        $form.find('#editGroup').val(userData.group);
        $form.find('#editScore').val(userData.score);
    }

    function openModal() {
        const $modal = $('#editUserModal');
        const $modal_overlay = $('.modal');
        $modal.addClass('active');
        $modal_overlay.addClass('active');

        $modal.on('click', function(e) {
            if (e.target === this) closeModal();
        });

        $('.modal-close-btn, .btn-cancel').on('click', closeModal);
        $('#editUserForm').on('submit', handleFormSubmit);
    }

    function closeModal() {
        const $modal = $('#editUserModal');
        const $modal_overlay = $('.modal');
        $modal.removeClass('active');
        $modal_overlay.removeClass('active');

        $modal.off('click');
        $('.modal-close-btn, .btn-cancel').off('click');
        $('#editUserForm').off('submit');
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        const formData = {
            uuid: $('#editUserId').val(),
            name: $('#editName').val(),
            sname: $('#editSName').val(),
            username: $('#editUsername').val(),
            mail: $('#editEmail').val(),
            role: $('#editRole').val(),
            group: $('#editGroup').val(),
            score: $('#editScore').val()
        };

        saveUserChanges(formData);
    }

    function saveUserChanges(formData) {
        showLoadingIndicator();

        $.post('/server/admin/users.php', formData)
            .done(function(response) {
                if (response.success) {
                    showMessage('Успех', 'Данные пользователя обновлены', 'success');
                    closeModal();
                    loadUsers();
                } else {
                    console.log(response);
                    showMessage('Ошибка', response.message || 'Ошибка при обновлении', 'error');
                }
            })
            .fail(handleUsersLoadError);
    }

    function setupEventListeners() {
        // Обработчик поиска
        $('#searchButton').on('click', handleSearch);
        $('#userSearch').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                handleSearch();
            }
        });
    }

    function handleSearch() {
        const searchTerm = $('#userSearch').val().trim();
        if (searchTerm.length > 0) {
            loadUsers({ search: searchTerm });
        } else {
            loadUsers();
        }
    }
    function showLoadingIndicator() {
        // Реализация показа индикатора загрузки
    }

    function showMessage(title, text, type) {
        let message = new Message();
        if (type === 'error') {
            message.showError(title, text);
        } else {
            message.showSuccess(title, text);
        }
        console.log(`${title}: ${text}`);
    }
});