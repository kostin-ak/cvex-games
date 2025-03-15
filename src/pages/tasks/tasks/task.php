<?php
include_once 'utils/account_utils.php';
include_once "utils/db_utils.php";
include_once "utils/utils.php";
include_once "utils/icon_chooser.php";

if(!AccountUtils::is_signed_in()){
    header("Location: /login?link=$_SERVER[REQUEST_URI]", true, 307);
    die();
}else{
    $task = DBUtils::getInstance()->getTaskByUUID($_GET["uuid"]);
    $passed = DBUtils::getInstance()->getPassedTaskCount($_GET["uuid"]);
    $firts_blood = DBUtils::getInstance()->getTaskFirstBlood($_GET["uuid"]);
    if (!$firts_blood){
        echo "ERROR!";
    }else {
        $firts_blood_user = User::getUserByArray($firts_blood);
    }
    $started = $_GET["started"];
}

function getTaskFilesBlock(Task $task){
    $text = "";
    if ($task->getAttachment() != ""){
        $attachment = $task->getAttachment();
        $attachment = explode(";", $attachment);

        $text = '<hr><div><h2>Файлы</h2><div class="files">';
        foreach ($attachment as $a){
            $filename = explode("/",$a);
            $filename = $filename[count($filename)-1];
            $type = explode(".", $filename);
            if (count($type) >= 1){
                $type = $type[count($type) - 1];
            }else{
                $type = "zip";
            }

            $text .= '
                    <div class="file">
                        <img src="'.IconChooser::chooseIcon($type).'" alt="">
                        <div>
                            <p class="file_name">'.$filename.'</p>
                            <a href="'.$a.'"
                               class="download" download>Скачать</a>
                        </div>
                    </div>   
                    ';
        }
        $text .= '</div></div>';

    }
    return $text;
}

function getTimeBlock(){
    echo "<p>Задание начато в: <span>datetime</span></p>
                <p>Осталось времени: <span>time</span></p>";
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
                        echo getTimeBlock($task);
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
            <?php
                if($started){
                    echo '
                        <div class="task_description">
                        <hr>
                        <p>'.$task->getTaskText().'</p>
                        '.getTaskFilesBlock($task).'
                    </div>
                    ';
                }else{
                    echo '<button class="start_button">Начать!</button>';
                }

            ?>

        </div>
    </div>
</body>
</html>