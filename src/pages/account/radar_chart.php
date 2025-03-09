<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Полярная диаграмма с линиями</title>
    <link rel="stylesheet" href="/pages/account/css/account.css">
    <link rel="stylesheet" href="/global/css/global.css">
    <link rel="stylesheet" href="/global/css/pages.css">
    <script src="/global/js/functions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #myRadarChart{
            color: red;
        }
    </style>
</head>
<body>
<div class="main">
<canvas id="myRadarChart" width="400" height="400"></canvas>
</div>
<script>
    // Ваши заранее заданные значения
    const labels = ['Значение 1', 'Значение 2', 'Значение 3', 'Значение 4', 'Значение 5'];
    const dataValues = [1, 2, 3, 4, 5]; // Замените на ваши значения

    // Создание полярной диаграммы
    const ctx = document.getElementById('myRadarChart').getContext('2d');
    const myRadarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Мои данные',
                data: dataValues,
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Цвет заполнения
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Цвет заполнения полигона
                borderColor: 'rgba(75, 192, 192, 1)', // Цвет линии
                borderWidth: 2,
                fill: true // Заполняем область под линией
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        display: true // Отображение меток на радиусе
                    }
                }
            }
        }
    });
</script>
</body>
</html>