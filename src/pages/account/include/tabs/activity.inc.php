<link rel="stylesheet" href="/pages/account/include/tabs/css/activity.css">

<?php
    $activities = DBUtils::getInstance()->results()->getCompletedByUser($_SESSION['user_uuid']);
    $activities = group_by_date($activities, "date");
    ksort($activities);
    $activities = array_reverse($activities);
?>

<div class="activity-account-block">
    <?php
    //print_r($activities);
        foreach ($activities as $date => $activity_array) {
            echo '
                <div class="activity-account-block-date">
                    <div class="date_splitter">
                        <span class="date">'.$date.'</span>
                        <hr>
                    </div>
            ';
            foreach ($activity_array as $activity) {
                //print_r($activity);
                echo '<div class="activity-account-block-item">
                        <div class="data">
                            <div class="name"><h3>Выполнение задания «'.$activity['task']['name'].'»</h3></div>
                            <div class="date">'.date("d.m.Y H:i", strtotime($activity['date'])).'</div>
                        </div>
                        <div class="score">+'.$activity['task']['value'].' '.number($activity['task']['value'], array("балл", "балла", "баллов")).'</div>
                    </div>';
            }
            echo '</div>';
        }
    ?>
</div>