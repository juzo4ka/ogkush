<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Проверяем, залогинился ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получаем ID пользователя из сессии
$user_id = $_SESSION['user_id'];

// Проверяем подключение к базе данных
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
} else {
    echo "Соединение с базой данных установлено успешно!<br>";
}

// Получаем заявления пользователя из базы данных
$sql = "SELECT * FROM statements WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Проверяем, есть ли заявления
if ($result->num_rows > 0) {
    echo "<h2>Ваши заявления</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>Номер автомобиля: " . $row['car_number'] . "<br>";
        echo "Описание: " . $row['description'] . "<br>";
        echo "Статус: " . $row['status'] . "</p><hr>";
    }
} else {
    echo "<p>У вас пока нет заявлений.</p>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ваши заявления</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Заявления</h1>
    <form method="post" action="add_statement.php">
        <input type="text" name="car_number" placeholder="Номер автомобиля" required>
        <textarea name="description" placeholder="Описание нарушения" required></textarea>
        <button type="submit">Отправить заявление</button>
    </form>
    <p><a href="logout.php">Выйти</a></p>
</body>
</html>
