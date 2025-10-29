<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// Simple routing
$page = $_GET['page'] ?? 'login';

// Initialize users array if not set
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

// -------- Registration Handling --------
if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check for existing username/email
    foreach ($_SESSION['users'] as $user) {
        if ($user['username'] === $username || $user['email'] === $email) {
            echo $twig->render('register.twig', [
                'title' => 'Register',
                'error' => 'Username or email already exists!',
                'old' => $_POST
            ]);
            exit;
        }
    }

    // Store user with hashed password
    $_SESSION['users'][] = [
        'fullname' => $fullname,
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    // Redirect to login with success message
    $_SESSION['success'] = 'Registration successful! You can login now.';
    header("Location: ?page=login");
    exit;
}

// -------- Login Handling --------
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $found = false;

    foreach ($_SESSION['users'] as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $found = true;
            header("Location: ?page=dashboard");
            exit;
        }
    }

    if (!$found) {
        echo $twig->render('login.twig', [
            'title' => 'Login',
            'error' => 'Invalid username or password!',
            'old' => $_POST
        ]);
        exit;
    }
}

// -------- Dashboard auth check --------
if ($page === 'dashboard') {
    if (!isset($_SESSION['user'])) {
        header("Location: ?page=login");
        exit();
    }
}

// -------- Logout Handling --------
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit();
}

// -------- Render templates --------
switch ($page) {
    case 'login':
        echo $twig->render('login.twig', [
            'title' => 'Login',
            'success' => $_SESSION['success'] ?? null
        ]);
        unset($_SESSION['success']);
        break;

    case 'register':
        echo $twig->render('register.twig', ['title' => 'Register']);
        break;

    case 'dashboard':
        echo $twig->render('dashboard.twig', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user']
        ]);
        break;

    default:
        echo $twig->render('login.twig', ['title' => 'Login']);
        break;
}
