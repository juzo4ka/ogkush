<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (full_name, phone, email, department, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullName, $phone, $email, $department, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Ошибка: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    <form method="post" action="register.php">
        <input type="text" name="full_name" placeholder="ФИО" required>
        <input type="text" name="phone" placeholder="Телефон" required>
        <input type="email" name="email" placeholder="Адрес электронной почты" required>
        <input type="text" name="department" placeholder="Отдел" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже зарегистрированы? <a href="login.php">Войти</a></p>
</body>
</html>