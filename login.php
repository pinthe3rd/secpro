<?php
session_start();
include 'config/functions.php';

define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION', 300); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];
    $remember_me_token = isset($_POST['remember']);
    if (!isLockedOut($nim)) {
        if (login($nim, $password, $remember_me_token)) {
            updateLoginAttempts($_SESSION['user']['id'], 0);
            
            session_regenerate_id(true);

            setcookie('user_id', $_SESSION['user']['id'], time() + 3600, '/'); // Cookie berlaku selama 1 jam

            header("Location: dashboard_" . $_SESSION['user']['role'] . ".php");
            exit();
        } else {
            
            $attempts = isset($_SESSION['user']) ? getLoginAttempts($nim) : 0;
            updateLoginAttempts($attempts + 1, $nim);
            
            usleep(500000); // 0.5 detik
            
            if ($attempts + 1 >= MAX_LOGIN_ATTEMPTS) {
                lockoutUser($nim);
                echo "Terlalu banyak percobaan login. Akun Anda telah terkunci. Silakan coba lagi nanti.";
            } else {
                echo "Login Gagal. Silakan coba lagi.";
            }
        }
    } else {
        echo "Akun Anda terkunci. Silakan coba lagi nanti.";
    }
}
header("Content-Security-Policy: default-src 'self';");


?>
