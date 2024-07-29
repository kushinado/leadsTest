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

// Запрос для выборки всех лидов
$sql = "SELECT * FROM leads";
$result = $conn->query($sql);

// Установка заголовков для скачивания файла
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=leads.csv');

// Создание указателя файла в памяти
$output = fopen('php://output', 'w');

// Запись для кодировки UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Запись заголовков столбцов
fputcsv($output, array('ФИО', 'Email', 'Телефон', 'Город'));

// Запись данных в CSV-файл
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

// Закрытие соединения с базой данных
fclose($output);
$conn->close();
?>
