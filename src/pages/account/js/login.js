$(window).on('load', function() {
    $("#submit").on('click', function(){
        check_user();
    });

    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            check_user();
        }
    });
});

function check_user(){
    var login = $("#email").val();
    var password = $("#password").val();
    var link = $("#link").val();

    if (link == "") link = "/";

    $.post('/server/auth.php', {login: login, password:password}, function(data){
        if (!data){
            $(".error").removeClass("hidden_block");
        }
        else if (data == "1"){
            //alert(data)
            window.location.href = link;
        }
        else {
            alert(data);
        }
    });
}

