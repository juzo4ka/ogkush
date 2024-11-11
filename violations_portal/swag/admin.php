<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Проверяем, является ли пользователь администратором
$admin_username = 'copp'; // Имя администратора
$admin_password = 'password'; // Пароль администратора

// Если администратор еще не авторизован
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin.php");
            exit();
        } else {
            $error_message = "Неверное имя пользователя или пароль.";
        }
    } else {
        $error_message = "";
    }

    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход в админ-панель</title>
    </head>
    <body>
        <h1>Вход в админ-панель</h1>
        <form method="post" action="admin.php">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        <?php if ($error_message) echo "<p style='color: red;'>$error_message</p>"; ?>
    </body>
    </html>
    <?php
    exit();
}

// Если админ авторизован, продолжаем отображение админ-панели
$sql = "SELECT requests.id, users.full_name, users.department, requests.category, requests.description, requests.status 
        FROM requests
        JOIN users ON requests.user_id = users.id";
$result = $conn->query($sql);

if ($result === false) {
    die("Ошибка выполнения запроса: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администраторская панель</title>
</head>
<body>
    <h2>Все заявки</h2>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <p>
            Пользователь: <?php echo $row['full_name']; ?><br>
            Отдел: <?php echo $row['department']; ?><br>
            Категория: <?php echo $row['category']; ?><br>
            Описание: <?php echo $row['description']; ?><br>
            Статус: <?php echo $row['status']; ?>
        </p>
        <a href="change_status.php?id=<?php echo $row['id']; ?>&status=новая">Новая</a> | 
        <a href="change_status.php?id=<?php echo $row['id']; ?>&status=в%20процессе">В процессе</a> | 
        <a href="change_status.php?id=<?php echo $row['id']; ?>&status=выполнена">Выполнена</a> | 
        <a href="change_status.php?id=<?php echo $row['id']; ?>&status=отменена">Отменена</a>
        <hr>
    <?php } ?>
    <p><a href="logout.php">Выйти</a></p>
</body>
</html>