<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM requests WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заявки</title>
</head>
<body>
    <h1>Мои заявки</h1>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <p>
            Категория: <?php echo $row['category']; ?><br>
            Описание: <?php echo $row['description']; ?><br>
            Статус: <?php echo $row['status']; ?>
        </p>
        <hr>
    <?php } ?>

    <h2>Новая заявка</h2>
    <form method="post" action="add_request.php">
        <input type="text" name="category" placeholder="Категория" required>
        <textarea name="description" placeholder="Описание проблемы" required></textarea>
        <button type="submit">Отправить заявку</button>
    </form>
    <p><a href="logout.php">Выйти</a></p>
</body>
</html>