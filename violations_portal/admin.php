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
    // Если данные отправлены из формы авторизации
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true; // Устанавливаем флаг авторизации
            header("Location: admin.php"); // Перезагружаем страницу
            exit();
        } else {
            $error_message = "Неверное имя пользователя или пароль.";
        }
    } else {
        $error_message = "";
    }

    // Если админ не авторизован, отображаем форму для входа
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

// Получаем все заявления из базы данных
$sql = "SELECT statements.id, users.full_name, statements.description, statements.car_number, statements.status 
        FROM statements
        JOIN users ON statements.user_id = users.id";
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
    <h2>Все заявления</h2>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <p>
            Пользователь: <?php echo $row['full_name']; ?><br>
            Номер автомобиля: <?php echo $row['car_number']; ?><br>
            Описание: <?php echo $row['description']; ?><br>
            Статус: <?php echo $row['status']; ?>
        </p>
        <a href="change_status.php?id=<?php echo $row['id']; ?>&status=confirmed">Подтвердить</a> | 
        <a href="change_status.php?id=<?php echo $row['id']; ?>&status=rejected">Отклонить</a>
        <hr>
    <?php } ?>
    <p><a href="logout.php">Выйти</a></p>
</body>
</html>
