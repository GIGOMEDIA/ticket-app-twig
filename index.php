<?php
require_once __DIR__ . '/vendor/autoload.php';

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Set to cache directory in production
]);

// Get current page from URL (default = login)
$page = $_GET['page'] ?? 'login';

// Simple routing system
switch ($page) {
    case 'login':
        echo $twig->render('login.twig', ['title' => 'Login']);
        break;

    case 'register':
        echo $twig->render('register.twig', ['title' => 'Register']);
        break;

    case 'dashboard':
        echo $twig->render('dashboard.twig', ['title' => 'Dashboard']);
        break;

    case 'tickets':
        // ✅ Ensure this template file exists: templates/ticket-list.twig
        echo $twig->render('ticket-list.twig', ['title' => 'Tickets']);
        break;

    case 'logout':
        // ✅ Clear localStorage and redirect to login
        echo "
        <script>
          localStorage.removeItem('user');
          window.location.href='?page=login';
        </script>";
        break;

    default:
        echo $twig->render('login.twig', ['title' => 'Login']);
        break;
}
