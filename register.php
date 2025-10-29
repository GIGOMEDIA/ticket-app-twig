<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = [];
    }

    $_SESSION['users'][$email] = [
        'password' => $password
    ];

    header("Location: ?page=login");
    exit();
}
