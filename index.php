<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// Initialize users array if not set
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

// Decode tickets from localStorage fallback (JS will supply later)
$ticketsJSON = $_GET['tickets'] ?? '[]';
$tickets = json_decode($ticketsJSON, true);

// Get page
$page = $_GET['page'] ?? 'login';

// -------- Registration Handling --------
if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check duplicate username/email
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

    // Save user
    $_SESSION['users'][] = [
        'fullname' => $fullname,
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

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

// -------- Logout --------
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit();
}

// -------- Render templates with session protection --------
switch ($page) {
    case 'login':
        echo $twig->render('login.twig', [
            'title' => 'Login',
            'success' => $_SESSION['success'] ?? null
        ]);
        unset($_SESSION['success']);
        break;

    case 'register':
        echo $twig->render('register.twig', [
            'title' => 'Register'
        ]);
        break;

    case 'dashboard':
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }
        echo $twig->render('dashboard.twig', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'],
            'tickets' => $tickets
        ]);
        break;

    case 'tickets':
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }
        echo $twig->render('ticket-list.twig', [
            'title' => 'Tickets',
            'user' => $_SESSION['user'],
            'tickets' => $tickets
        ]);
        break;

    default:
        echo $twig->render('login.twig', ['title' => 'Login']);
        break;
}
