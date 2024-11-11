<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $sql = "INSERT INTO requests (user_id, category, description, status) VALUES (?, ?, ?, 'новая')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $category, $description);

    if ($stmt->execute()) {
        header("Location: requests.php");
        exit();
    } else {
        echo "Ошибка: " . $stmt->error;
    }
} else {
    echo "Форма не была отправлена корректно.";
}
?>