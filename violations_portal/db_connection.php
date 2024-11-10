<?php
$servername = "localhost";
$username = "root"; // Обычно "root" для MAMP
$password = "root"; // Обычно "root" для MAMP
$dbname = "violations_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
