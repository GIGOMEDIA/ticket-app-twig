<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        // Save user data in session (acts like local storage)
        $_SESSION['registered_users'][$username] = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        // Auto login after registration
        $_SESSION['user'] = ['username' => $username];
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $_SESSION['error'] = 'Please fill in all fields.';
        header('Location: index.php?page=register');
        exit;
    }
}
