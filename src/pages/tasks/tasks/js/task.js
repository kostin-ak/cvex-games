$(document).ready(function (){
    var uuid = $("#taskUuid").val();

    getState(uuid);
});


$(".start_button").on("click", function () {
    var uuid = $("#taskUuid").val();
    startTask(uuid);
});

function startTask(uuid){
    data = {
        "start": 1,
        "task_uuid": uuid
    }
    $.ajax({
        url: '/server/task.php',
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(response) {
            renderTask(response.data);
        },
        error: function(xhr) {
            showError(xhr.responseJSON?.message || 'Произошла ошибка. Попробуйте позже.');
        }
    });
}

function getState(uuid){
    data = {
        "state": 1,
        "task_uuid": uuid
    }
    $.ajax({
        url: '/server/task.php',
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.data != false) renderTask(response.data);
        },
        error: function(xhr) {
            showError(xhr.responseJSON?.message || 'Произошла ошибка. Попробуйте позже.');
        }
    });
    return false;
}

function checkTask(uuid){
    answer = $("#answerTask").val();
    data = {
        "check": 1,
        "task_uuid": uuid,
        "answer": answer
    }
    $.ajax({
        url: '/server/task.php',
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.data){
                location.reload();
            }else{
                alert("Не верно!");
            }
        },
        error: function(xhr) {
            showError(xhr.responseJSON?.message || 'Произошла ошибка. Попробуйте позже.');
        }
    });
    return false;
}

function renderTask(data){
    $(".button_holder").removeClass("active");
    $(".task_description").addClass("active");
    $(".answer-block").addClass("active");
    $(".task_description").html("<hr>"+data+"<hr>");
    console.log(data);

    $(".check-task").on("click", function (){
        var uuid = $("#taskUuid").val();
        checkTask(uuid);
    });
}

function showError(message){
    console.error(message);
}
