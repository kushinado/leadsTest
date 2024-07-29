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

// Получение IP-адреса пользователя и текущего времени
$ip_address = $_SERVER['REMOTE_ADDR'];
$submission_time = date('Y-m-d H:i:s');

// Проверка количества заявок с одного IP за последний час
$sql_check = "SELECT COUNT(*) as submission_count FROM submissions WHERE ip_address = '$ip_address' AND submission_time > (NOW() - INTERVAL 1 HOUR)";
$result_check = $conn->query($sql_check);

// Проверка успешности выполнения запроса
if (!$result_check) {
    die("Ошибка выполнения запроса: " . $conn->error);
}

$row_check = $result_check->fetch_assoc();
// Проверка, если количество заявок превышает 5
if ($row_check['submission_count'] >= 5) {
    // Проверка, заблокирован ли пользователь
    $sql_block = "SELECT MAX(submission_time) as last_submission FROM submissions WHERE ip_address = '$ip_address'";
    $result_block = $conn->query($sql_block);
    
    // Проверка успешности выполнения запроса
    if (!$result_block) {
        die("Ошибка выполнения запроса: " . $conn->error);
    }
    
    $row_block = $result_block->fetch_assoc();
    $last_submission = strtotime($row_block['last_submission']);
    $current_time = strtotime($submission_time);

    // Проверка, прошло ли меньше 2 часов с последней заявки
    if (($current_time - $last_submission) <= 7200) { // 7200 секунд = 2 часа
        echo "Ваша форма заблокирована на 2 часа из-за превышения лимита заявок.";
        $conn->close();
        exit;
    }
}

// Сохранение данных о заявке
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];

// Запросы для сохранения данных в таблицы leads и submissions
$sql_lead = "INSERT INTO leads (name, email, phone, city) VALUES ('$name', '$email', '$phone', '$city')";
$sql_submission = "INSERT INTO submissions (ip_address, submission_time) VALUES ('$ip_address', '$submission_time')";

// Проверка успешности выполнения запросов
if ($conn->query($sql_lead) === TRUE && $conn->query($sql_submission) === TRUE) {
    echo "Вы успешно добавили новую запись";
} else {
    echo "Ошибка: " . $conn->error;
}

// Закрытие соединения с базой данных
$conn->close();
?>
