<?php
$servername = "localhost";
$username = "root"; // Заменете ако користите друг корисник
$password = ""; // Заменете ако имате лозинка за MySQL
$dbname = "football_store"; // Името на вашата база

// Создавање на конекција
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Проверка на конекцијата
if (!$conn) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}
?>
