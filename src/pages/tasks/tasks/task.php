<?php
include_once 'utils/account_utils.php';
include_once "utils/db_utils.php";
include_once "utils/utils.php";
include_once "utils/icon_chooser.php";

if(!AccountUtils::is_signed_in()){
    header("Location: /login?link=$_SERVER[REQUEST_URI]", true, 307);
    die();
}else{
    $task = DBUtils::getInstance()->tasks()->getByUUID($_GET["uuid"], $_SESSION['user_uuid']);
    $passed = DBUtils::getInstance()->tasks()->getPassedCount($_GET["uuid"]);
    $firts_blood = DBUtils::getInstance()->tasks()->getFirstBlood($_GET["uuid"]);
    if (!$firts_blood){
        echo "ERROR!";
    }else {
        $firts_blood_user = User::fromData($firts_blood);
    }
    $started = $_GET["started"];
}

function getDifficulty(Task $task){
    $diffs = array(
        0 => "joke",
        1 => "easy",
        2 => "medium",
        3 => "hard",
        4 => "impossible"
    );
    return $diffs[$task->getDifficulty()];
}

function getTimeBlock(){
    echo "<p>Задание начато в: <span>datetime</span></p>
                <p>Осталось времени: <span>time</span></p>";
}


function getTime(Task $task){
    if ($task->getTimeLimit() != 0) return "limited";
}

?>

<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/pages/tasks/tasks/css/task.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $task->getName()?></title>

</head>
<body>
    <div class="main">
        <input type="hidden" id="taskUuid" value="<?php echo $task->getUuid()?>">
        <div class="task_info">
            <div class="first_blood card inner_block">
                <span class="blood">🩸</span>
                <div>
                    <?php
                        if ($firts_blood){
                            echo '
                                <p>Первая кровь: <a href=""><span>#'.$firts_blood_user->getUsername().'</span></a></p>
                                <p><span class="date">'.date("d.m.Y H:m",strtotime($firts_blood["first_blood_date"])).'</span></p>
                            ';
                        }else{
                            echo '<p>
                                    Ещё никто не решил это задание!<br>Будьте первым!
                                    </p>';
                        }
                    ?>

                </div>
            </div>
            <div class="started card inner_block">
                <span class="completed">📝</span>
                <div>
                    <?php
                        #echo getTimeBlock($task);
                    ?>
                </div>
            </div>
            <div class="passed card inner_block">
                <span class="score">🏆</span>
                <div>
                    <p>Выполнили задание: <span><?php echo $passed["count"]; ?></span></p>
                    <p>Награда: <span><?php echo $task->getValueWithMul()?></span></p>
                </div>
            </div>
        </div>
        <div class="task card">
            <div class="info">
                <h1><?php echo $task->getName()?></h1>
                <div class="markers">
                    <p class="difficulty <?php echo getDifficulty($task)?>"></p>
                    <p class="time <?php echo getTime($task)?>"></p>
                </div>
            </div>
            <div class="about_task">
                <p><?php echo $task->getDescription()?></p>
            </div>
            <div>
                <div class="task_description">
                    <hr>
                </div>
                <?php
                    if ($task->isCompleted()){
                        echo '<div class="task-state completed">Выполнено</div>';
                    }else if ($task->isInProgress()){
                    }else{
                        echo '<div class="button_holder active"><button class="start_button">Начать!</button></div>';
                        #Answer for task 37
                    }
                ?>
                <div class="answer-block">
                    <input type="text" id="answerTask">
                    <button class="check-task">Проверить?</button>
                </div>

            </div>
        </div>
    </div>
    <script src="/pages/tasks/tasks/js/task.js"></script>
</body>
</html>