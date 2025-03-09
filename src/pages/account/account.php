<?php
    include_once 'utils/account_utils.php';
    include_once "utils/db_utils.php";
    include_once "utils/utils.php";

    if(!AccountUtils::is_signed_in()){
        header("Location: /login?link=/account", true, 307);
        die();
    }

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/account/css/account.css">
    <link rel="stylesheet" href="/global/css/global.css">
    <link rel="stylesheet" href="/global/css/pages.css">
    <script src="/global/js/functions.js"></script>
    <title>Аккаунт</title>
</head>
<body>
    <div class="main">
        <h1>Аккаунт</h1>
        <div class="card info_block">
            <div class="account-info-block">
                <div class="imgholder">
                    <img src="/global/images/account.svg" alt="">
                </div>
                <div class="account-info">
                    <b><p class="type <?php echo AccountUtils::get_user()->getRole()->getClass();?>"><?php echo AccountUtils::get_user()->getRole()->getDescription()?></p></b>
                    <p class="name"><?php echo AccountUtils::get_user()->getName()." ".AccountUtils::get_user()->getSname();?></p>
                    <p class="username">#<?php echo AccountUtils::get_user()->getUsername();?></p>
                    <p class="registered"><?php echo time_interval_to_string(get_time_delta(AccountUtils::get_user()->getRegistered()));?> с нами</p>
                </div>
            </div>
            <div class="block_score">
                <div class="card-primary block_score_inner">
                    <div>
                        <img src="https://imgholder.ru/48x48/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson" alt="">
                        <p>Заработано очков: </p>
                    </div>
                    <p class="accent"><?php echo AccountUtils::get_user()->getScore(); ?></p>

                </div>
                <div class="card-primary block_score_inner">
                    <div>
                        <img src="https://imgholder.ru/48x48/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson" alt="">
                        <p>Рейтинг: </p>
                    </div>
                    <p class="accent"><?php echo DBUtils::getInstance()->getRankUserByUUID($_SESSION['user_uuid']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
