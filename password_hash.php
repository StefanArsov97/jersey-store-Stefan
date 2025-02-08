<?php
// Пример за хаширање на нова лозинка
$newPassword = "your_new_password";
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

echo "Hashed Password: " . $hashedPassword;
?>
