<?php
    $categories_stats = DBUtils::getInstance()->getPercentageOfPassedTasksByCategories($_SESSION['user_uuid']);
?>

<canvas id="radarChart" class="radar-chart"></canvas>

<script>

    const style = getComputedStyle(document.body);

    // Ваши заранее заданные значения
    const labels = <?php printArrayFormatted(array_keys($categories_stats));?>;
    const dataValues = <?php printArrayFormatted(array_values($categories_stats));?>;

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