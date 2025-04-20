<link rel="stylesheet" href="/pages/account/include/tabs/css/activity.css">

<?php
$activities = DBUtils::getInstance()->results()->getCompletedByUser($_SESSION['user_uuid']);
$activities = group_by_date($activities, "date");
?>

<div class="activity-container">
    <?php if (!empty($activities)): ?>
        <?php foreach ($activities as $date => $activity_array): ?>
            <section class="activity-date-group">
                <h2 class="date-header">
                    <svg class="calendar-icon" viewBox="0 0 24 24" width="20" height="20">
                        <path fill="currentColor" d="M7,11H9V13H7V11M21,5V19C21,20.11 20.11,21 19,21H5C3.89,21 3,20.1 3,19V5C3,3.9 3.9,3 5,3H6V1H8V3H16V1H18V3H19C20.11,3 21,3.9 21,5M5,7H19V5H5V7M19,9H5V19H19V9M15,13V11H17V13H15M11,13V11H13V13H11M7,15H9V17H7V15M15,17V15H17V17H15M11,17V15H13V17H11Z"/>
                    </svg>
                    <?= ucfirst(strftime('%d %B %Y', strtotime($date))) ?>
                </h2>
                <div class="activity-list">
                    <?php foreach ($activity_array as $activity): ?>
                        <article class="activity-card card">
                            <div class="activity-info">
                                <h3 class="activity-title">
                                    <svg class="task-icon" viewBox="0 0 24 24" width="18" height="18">
                                        <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                                    </svg>
                                    <span class="title-highlight">Вы успешно выполнили:</span>
                                    <span class="task-name">«<?= htmlspecialchars($activity['task']['name']) ?>»</span>
                                </h3>
                                <div class="activity-meta">
                                    <span class="time-info">
                                        <svg class="clock-icon" viewBox="0 0 24 24" width="14" height="14">
                                            <path fill="currentColor" d="M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M12.5,7V12.25L17,14.92L16.25,16.15L11,13V7H12.5Z"/>
                                        </svg>
                                        <?= date("H:i", strtotime($activity['date'])) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="activity-score">
                                +<?= $activity['task']['value'] ?> <?= number($activity['task']['value'], ["балл", "балла", "баллов"]) ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" width="64" height="64">
                <path fill="currentColor" d="M12,3L1,9L12,15L21,10.09V17H23V9M5,13.18V17.18L12,21L19,17.18V13.18L12,17L5,13.18Z"/>
            </svg>
            <p class="empty-text">Здесь будет отображаться ваша активность</p>
        </div>
    <?php endif; ?>
</div>