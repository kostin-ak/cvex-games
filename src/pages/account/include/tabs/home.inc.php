<?php
$categories_stats_percent = DBUtils::getInstance()->results()->getPercentagePassedByCategories($_SESSION['user_uuid']);
$categories_stats = DBUtils::getInstance()->results()->getCountPassedAndTotalByCategories($_SESSION['user_uuid'], $_SESSION['user']['group']);
?>

<link rel="stylesheet" href="/pages/account/include/tabs/css/home.css">

<div class="home-account-block">
    <div class="radar-chart-container">
        <canvas id="radarChart" class="radar-chart"></canvas>
    </div>

    <div class="categories-list">
        <?php foreach ($categories_stats as $key => $category):
            $percent = round($category['passed']/$category['total']*100);
            $percentage_display = min(max($percent, 0), 100); // Ограничиваем процент от 0 до 100
            ?>
            <div class="category-stats">
                <div class="category-data">
                    <span class="category-name"><?= htmlspecialchars($key) ?></span>
                    <div class="category-count">
                        <span class="number">[<?= $category['passed'] ?>/<?= $category['total'] ?>]</span>
                        <span class="percent">(<?= $percentage_display ?>%)</span>
                    </div>
                </div>
                <div class="category-progress-bar">
                    <div class="category-progress" style="width: <?= $percentage_display ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    const style = getComputedStyle(document.body);

    const labels = <?php printArrayFormatted(array_keys($categories_stats_percent));?>;
    const dataValues = <?php printArrayFormatted(array_values($categories_stats_percent));?>;

    Chart.defaults.backgroundColor = style.getPropertyValue("--color--primary");
    Chart.defaults.borderColor = style.getPropertyValue("--color--primary");
    Chart.defaults.color = style.getPropertyValue("--text-color--primary");

    const ctx = document.getElementById('radarChart').getContext('2d');
    const myRadarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Результаты',
                data: dataValues,
                backgroundColor: `rgba(${style.getPropertyValue("--color--primary--alpha")}, 0.2)`,
                borderColor: style.getPropertyValue("--color--primary"),
                borderWidth: 2,
                pointBackgroundColor: style.getPropertyValue("--color--primary"),
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
                pointHoverBackgroundColor: style.getPropertyValue("--color--primary"),
                pointHoverBorderColor: '#fff',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 12
                    },
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw}%`;
                        }
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    min: 0,
                    ticks: {
                        backdropColor: 'transparent',
                        stepSize: 20,
                        color: style.getPropertyValue("--text-color--primary"),
                        font: {
                            size: 11
                        }
                    },
                    angleLines: {
                        color: style.getPropertyValue("--text-color--secondary")
                    },
                    grid: {
                        color: style.getPropertyValue("--text-color--secondary")
                    },
                    pointLabels: {
                        font: {
                            size: 13,
                            weight: '500'
                        },
                        color: style.getPropertyValue("--text-color--primary")
                    }
                }
            }
        }
    });
</script>