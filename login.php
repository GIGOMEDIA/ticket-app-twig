<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($_SESSION['users'][$email]) &&
        $_SESSION['users'][$email]['password'] === $password) {

        $_SESSION['user'] = $email;
        header("Location: ?page=dashboard");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: ?page=login");
        exit();
    }
}
