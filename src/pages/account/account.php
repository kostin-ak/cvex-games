<?php include_once 'utils/account_utils.php';

    if(!AccountUtils::is_signed_in()){
        header("Location: /login?link=/account", true, 307);
        die();
    }

?>

<?php if (AccountUtils::is_signed_in()): ?>

    <!doctype html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Аккаунт</title>
    </head>
    <body>
        <h1>Аккаунт</h1>
    </body>
    </html>

<?php else: ?>



<?php endif ?>