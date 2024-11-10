<?php
// Включаем отображение ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Проверяем подключение к базе данных
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
} else {
    echo "Соединение с базой данных успешно установлено!<br>";
}

// Проверка отправки формы методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Форма отправлена.<br>"; // Проверка отправки формы

    // Получение данных из формы
    $fullName = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    echo "Данные получены: $fullName, $phone, $email, $username<br>"; // Вывод полученных данных

    // Проверка, существует ли уже такой email в базе данных
    $check_email = "SELECT * FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($check_email);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo "Ошибка: Пользователь с таким email уже существует. Пожалуйста, используйте другой email.";
    } else {
        // Подготовка SQL-запроса для регистрации
        $sql = "INSERT INTO users (full_name, phone, email, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Ошибка подготовки SQL-запроса: " . $conn->error);
        } else {
            echo "Запрос успешно подготовлен.<br>";
        }

        // Привязываем параметры и выполняем запрос
        $stmt->bind_param("sssss", $fullName, $phone, $email, $username, $password);
        if ($stmt->execute()) {
            echo "Регистрация успешна! Перенаправление на страницу входа...<br>";
            header("Location: login.php"); // Перенаправление на страницу входа
            exit();
        } else {
            echo "Ошибка выполнения SQL-запроса: " . $stmt->error;
        }
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
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже зарегистрированы? <a href="login.php">Войти</a></p>
</body>
</html>
