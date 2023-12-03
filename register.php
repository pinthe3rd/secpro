<?php
session_start();
include 'config/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nim = $_POST['nim'];
    $password = $_POST['password'];
    
    if (isNIMExist($nim)) {
        echo "NIM sudah digunakan. Silakan gunakan NIM lain.";
    } else {
        if (register($username, $nim, $password)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Registrasi Gagal";
        }
    }
}
header("Content-Security-Policy: default-src 'self';");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/register.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <label>Username:</label>
            <input type="text" name="username" required><br>
            <label>NIM:</label>
            <input type="text" name="nim" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
