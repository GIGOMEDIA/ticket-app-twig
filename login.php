<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check if user exists
    if (isset($_SESSION['registered_users'][$username])) {
        $storedUser = $_SESSION['registered_users'][$username];

        if (password_verify($password, $storedUser['password'])) {
            // Login successful
            $_SESSION['user'] = ['username' => $username];
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid password.';
        }
    } else {
        $_SESSION['error'] = 'User not found.';
    }

    header('Location: index.php?page=login');
    exit;
}
