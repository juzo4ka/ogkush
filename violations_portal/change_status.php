<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Проверяем, залогинился ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// Проверка, что параметры id и status переданы
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id']; // Приводим id к целому числу для безопасности
    $status = $_GET['status'];

    // Подготовка SQL-запроса для обновления статуса заявления
    $sql = "UPDATE statements SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Ошибка подготовки SQL-запроса: " . $conn->error);
    }

    // Привязываем параметры и выполняем запрос
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        // После успешного обновления перенаправляем обратно в админ-панель
        header("Location: admin.php");
        exit();
    } else {
        echo "Ошибка при обновлении статуса заявления: " . $stmt->error;
    }
} else {
    echo "Неверные параметры запроса.";
}
?>
