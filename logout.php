<?php
session_start();

$_SESSION = array();
session_destroy();

if (isset($_COOKIE['remember'])) {
    unset($_COOKIE['remember']);
    setcookie('remember', '', time() - 10, '/');
}

header("Location: index.php");
exit();
?>
