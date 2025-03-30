<?php
    $categories_stats_percent = DBUtils::getInstance()->results()->getPercentagePassedByCategories($_SESSION['user_uuid']);
    $categories_stats = DBUtils::getInstance()->results()->getCountPassedAndTotalByCategories($_SESSION['user_uuid'], $_SESSION['user']['group']);
?>


<link rel="stylesheet" href="/pages/account/include/tabs/css/home.css">

<div class="home-account-block">
    <canvas id="radarChart" class="radar-chart"></canvas>
    <div class="categories-list">
        <?php
        foreach ($categories_stats as $key => $category) {
            $percent = round($category['passed']/$category['total']*100);
            echo '<div class="category-stats">
                    <div class="category-data">
                        <div class="category-name">'.$key.'</div>
                        <div class="category-count">
                           <div class="number">['.$category['passed']. "/" . $category['total'].']</div>
                           <div class="percent">('.$percent.'%)</div>                     
                        </div>
                    </div>
                    <div class="category-progress-bar">
                        <div class="category-progress" style="width: '.$percent.'%"></div>
                    </div>
                </div>';
        }
        ?>
    </div>
</div>


<script>

    const style = getComputedStyle(document.body);

    // Ваши заранее заданные значения
    const labels = <?php printArrayFormatted(array_keys($categories_stats_percent));?>;
    const dataValues = <?php printArrayFormatted(array_values($categories_stats_percent));?>;

    Chart.defaults.backgroundColor = style.getPropertyValue("--color--primary");
    Chart.defaults.borderColor = style.getPropertyValue("--color--primary");
    Chart.defaults.color = style.getPropertyValue("--text-color--primary");

    // Создание полярной диаграммы
    const ctx = document.getElementById('radarChart').getContext('2d');
    const myRadarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Результаты',
                data: dataValues,
                //backgroundColor: 'rgba('+style.getPropertyValue("--color--primary")+', 0.2)', // Цвет заполнения
                backgroundColor: 'rgba('+style.getPropertyValue("--color--primary--alpha")+', 0.2)', // Цвет заполнения полигона
                borderColor: style.getPropertyValue("--color--primary"), // Цвет линии
                borderWidth: 2,
                fill: true // Заполняем область под линией
            }]
        },
        options: {
            responsive: false,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        display: true, // Отображение меток на радиусе
                        showLabelBackdrop: false,
                    }
                }
            }
        }
    });
</script>
<div>

</div>