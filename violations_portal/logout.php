<?php
session_start();
session_unset(); // Удаляем все переменные сессии
session_destroy(); // Завершаем сессию

// Перенаправляем пользователя на страницу входа после выхода из системы
header("Location: login.php");
exit();
