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

// Проверяем, отправлена ли форма методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $car_number = $_POST['car_number'];
    $description = $_POST['description'];

    // Подготовка и выполнение SQL-запроса для добавления нового заявления
    $sql = "INSERT INTO statements (user_id, car_number, description, status) VALUES (?, ?, ?, 'new')";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("iss", $user_id, $car_number, $description);

    if ($stmt->execute()) {
        // Перенаправляем пользователя обратно на страницу statements.php после успешного добавления заявления
        header("Location: statements.php");
        exit();
    } else {
        echo "Ошибка при добавлении заявления: " . $stmt->error;
    }
} else {
    echo "Форма не была отправлена корректно.";
}
?>
