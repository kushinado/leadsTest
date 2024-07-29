<?php
// Подключение к БД
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leads_db1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение значения фильтра, если тот установлен
$city_filter = isset($_GET['city']) ? $_GET['city'] : '';

// Запрос для выборки записей по фильтру
$sql = "SELECT * FROM leads";
if ($city_filter) {
    $sql .= " WHERE city='$city_filter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список лидов</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Список лидов</h1>
        <!-- Форма с фильтром по городам -->
        <form method="GET" action="leads.php">
            <label for="city">Фильтр по городу:</label>
            <select id="city" name="city">
                <option value="">Все города</option>
                <option value="Москва">Москва</option>
                <option value="Санкт-Петербург">Санкт-Петербург</option>
                <option value="Тула">Тула</option>
            </select>
            <!-- Кнопка ФИЛЬТРОВАТЬ -->
            <button type="submit">Фильтровать</button>
        </form>
        <!-- Таблица со списком -->
        <table>
            <tr>
                <th>ФИО</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Город</th>
            </tr>
            <!-- Отрисовка строк таблицы в зависимости от кол-ва записей в БД -->
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['name']."</td><td>".$row['email']."</td><td>".$row['phone']."</td><td>".$row['city']."</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Нет данных</td></tr>";
            }
            ?>
        </table>
        <!-- Кнопка экспорта списка в CSV формат -->
        <a href="export.php" class="export-btn">Экспорт в CSV</a>
        <!-- Кнопка перехода на страницу index.html -->
        <a href="index.html" class="navigation-link">Форма сбора лидов</a>
    </div>
</body>
</html>

<!-- Закрытие соединения с БД -->
<?php
$conn->close();
?>
