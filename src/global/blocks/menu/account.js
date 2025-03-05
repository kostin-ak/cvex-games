$(window).on('load', function() {
    $(".account_menu .logout").on('click', function(){
        logout();
    });

});

function logout(){
    $.post('/server/auth.php', {logout: true}, function(data){
        location.reload();
    });
}

