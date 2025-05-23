<?php
    include_once 'utils/account_utils.php';
    include_once "utils/db_utils.php";
    include_once "utils/utils.php";
    include_once "entities/models/result.php";

    $url = $_SERVER['REQUEST_URI'];

    if(!AccountUtils::is_signed_in()){
        header("Location: /login?link=/account", true, 307);
        die();
    }

    function generate_tab($url){
        include_once 'pages/account/include/tabs.inc.php';
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
    <script src="/global/js/chart.umd.min.js"></script>
    <title>Аккаунт</title>
</head>
<body>
    <div class="main">
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
                        <span class="material-icons score-icon">emoji_events</span>
                        <p>Заработано очков: </p>
                    </div>
                    <p class="accent"><?php echo AccountUtils::get_user()->getScore(); ?></p>
                </div>
                <div class="card-primary block_score_inner">
                    <div>
                        <span class="material-icons rank-icon">leaderboard</span>
                        <p>Рейтинг: </p>
                    </div>
                    <p class="accent"><?php echo DBUtils::getInstance()->users()->getRankByUUID($_SESSION['user_uuid']); ?></p>
                </div>
            </div>
        </div>
        <div class="tabs">
            <?php
                generate_tab($url);
            ?>
        </div>
    </div>
</body>
</html>
