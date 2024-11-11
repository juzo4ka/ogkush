<?php
session_start();
include 'db_connection.php';

// Проверяем, является ли пользователь администратором
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// Проверка, что параметры id и status переданы
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id']; // Приводим id к целому числу для безопасности
    $status = $_GET['status'];

    // Подготовка SQL-запроса для обновления статуса заявки
    $sql = "UPDATE requests SET status = ? WHERE id = ?";
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
        echo "Ошибка при обновлении статуса заявки: " . $stmt->error;
    }
} else {
    echo "Неверные параметры запроса.";
}
?>