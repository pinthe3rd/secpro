<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard_" . $_SESSION['user']['role'] . ".php");
    exit();
}
header("Content-Security-Policy: default-src 'self';");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/login.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label>NIM</label>
            <input type="text" name="nim" required><br>
            <label>Password</label>
            <input type="password" name="password" required><br>
            <div class="remember">
                <input type="checkbox" class ="remember" name="remember"> Remember Me
            </div>
            <button type="submit">Login</button>
            <p>Belum punya akun? <a href="register.php">Register</a></p>
        </form>
    </div>
    
</body>
</html>
