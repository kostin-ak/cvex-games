$(document).ready(function (){
    $(".modal").html(
        '    <div class="modal-content">\n' +
        '        <div class="close-btn-and-title">' +
        '            <h2 id="modalTitle">Добавить категорию</h2>\n' +
        '            <button class="close-btn">×</button>\n' +
        '        </div>' +
        '        <form id="categoryForm">\n' +
        '            <input type="hidden" id="categoryUuid">\n' +
        '            <div class="form-group">\n' +
        '                <label for="categoryName">Название:</label>\n' +
        '                <input type="text" id="categoryName" required>\n' +
        '            </div>'+
        '            <div class="form-group">\n' +
        '                <label for="categoryDescription">Описание:</label>\n' +
        '                <textarea id="categoryDescription" rows="4"></textarea>\n' +
        '            </div>'+
        '            <div class="form-group">\n' +
        '                <label for="categoryImage">URL изображения:</label>\n' +
        '                <input type="text" id="categoryImage">\n' +
        '            </div>'+
        '            <div class="form-group checkbox-group">\n' +
        '                <input type="checkbox" checked id="categoryPublic">\n' +
        '                <label for="categoryPublic">Публичная категория</label>\n' +
        '            </div>'+
        '            <div class="form-group checkbox-group">\n' +
        '                <input type="checkbox" checked id="categoryDev">\n' +
        '                <label for="categoryDev">В разработке</label>\n' +
        '            </div>'+
        '            <div class="form-actions">\n' +
        '                <button type="button" id="cancelBtn" class="btn-secondary">Отмена</button>\n' +
        '                <button type="button" id="saveBtn" class="btn-primary">Сохранить</button>\n' +
        '            </div>\n' +
        '        </form>\n' +
        '    </div>\n'
    );
    $(".modal").hide();
    loadCategories();
});

$("#addCategoryBtn").on("click", function (){
   openModal();
});
$(".modal").on("click", function (e){
    if ($(".close-btn").is(e.target) || $("#cancelBtn").is(e.target)){
        closeModal();
    }

    if ($("#saveBtn").is(e.target)){
        changeCategory(getModal());
    }

    if ( !$(".modal-content").is(e.target) && $(".modal-content").has(e.target).length === 0 ) {
        closeModal();
    }

});


function openModal(){
    $(".modal").fadeIn();
}
function closeModal(){
    $(".modal").fadeOut();
    setModal("", "", "", "", true, true);
}
function loadCategories(){
    $.ajax({
        url: '/server/admin/categories.php',
        method: 'get',
        dataType: 'json',
        success: function (data){
            renderCategories(data.data.categories);
        },
        error: function (error){
            printError(error.responseJSON.error)
        }
    });
}

function renderCategories(categories){
    let data = [];
    categories.forEach(category => {
        console.log(category);
        image_block = (category.image != "") ? '<div class="category-image"><img src="'+category.image+'" alt="'+category.name+'"></div>' : "";
        tag1 = category.in_dev ? '<span class="tag in-dev">В разработке</span>': "";
        tag2 = !category.is_public ? '<span class="tag private">Приватная</span>': "";
        data_prop = ' data-category-uuid="'+category.uuid+'" data-category-name="'+escapeHtml(category.name)+'" data-category-description="'+escapeHtml(category.description)+'"+ data-category-img="'+category.image+'" + data-category-public="'+category.is_public+'" + data-category-in_dev="'+category.in_dev+'"'
        element = '<a class="card category-item">' +
            '            <div class="category" '+data_prop+'>' +
            '              <div class="tools">' +
            '               <div>'+
            '                   <button class="edit-button">' +
            '            <i class="fas fa-edit"></i>' +
            '           </button>\n' +
            '            <button class="delete-button">' +
            '               <i class="fas fa-trash-alt"></i>' +
            '           </button>'+
            '           </div>'+
            '           <div>'+tag1+tag2+'</div>'+
            '           </div>'+
            '                <div class="category-text">' +
            '                   <div class="info">'+
            '                       <h1>'+category.name+'</h1>' +
            '                   </div>'+
            '                    <p>'+category.description+'</p>\n' +
            '                </div>' +
                            image_block +
            '            </div>\n' +
            '        </a>'
        data.push(element);
    });
    data = data.join("");
    $(".categories").html(data);

    $(".edit-button").on("click", function (){
        startEdit($(this).parent().parent().parent());
    });

    $(".delete-button").on("click", function (){
        deleteCategoty($(this).parent().parent().parent().data("category-uuid"));
    });
}

function printError(error){
    console.error("Error!", error);
    let message = new Message();
    message.showError("Error", error);
}

function startEdit(element){
    uuid = element.data("category-uuid");
    name = element.data("category-name");
    description = element.data("category-description");
    img = element.data("category-img");
    is_public = element.data("category-public");
    in_dev = element.data("category-in_dev");
    setModal(uuid, name, description, img, is_public, in_dev);
    openModal();
}

function setModal(uuid, name, description, img, public, in_dev){
    $("#categoryName").val(name);
    $("#categoryDescription").val(description);
    $("#categoryImage").val(img);
    $("#categoryPublic").prop("checked", public);
    $("#categoryDev").prop("checked", in_dev);
    $("#categoryUuid").val(uuid);
}
function getModal(){
    var data = new Object();
    data.name = $("#categoryName").val();
    data.description = $("#categoryDescription").val();
    data.image = $("#categoryImage").val();
    data.is_public = $("#categoryPublic").prop("checked");
    data.in_dev = $("#categoryDev").prop("checked");
    data.uuid = $("#categoryUuid").val();

    return data;
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/`/g, '&#x60;');
}

function changeCategory(data){
    if (data.name != "") {
        $.ajax({
            url: '/server/admin/categories.php?change=1',
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (data) {
                closeModal();
                loadCategories();
            },
            error: function (error) {
                printError(error.responseJSON.error)
            }
        });
    }else{
        printError("Заполните название категории!")
    }
}

function deleteCategoty(uuid){
    if (uuid != ''){
        $.ajax({
            url: '/server/admin/categories.php?delete=1',
            method: 'post',
            dataType: 'json',
            data: {uuid: uuid},
            success: function (data) {
                loadCategories();
            },
            error: function (error) {
                printError(error.responseJSON.error)
            }
        });
    }else{
        printError("Заполните название категории!")
    }
}