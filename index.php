<?php
require_once __DIR__ . '/vendor/autoload.php';

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// Decode tickets from localStorage fallback (JS will supply later)
$ticketsJSON = $_GET['tickets'] ?? '[]';
$tickets = json_decode($ticketsJSON, true);

// Simple routing
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        echo $twig->render('login.twig', ['title' => 'Login']);
        break;

    case 'register':
        echo $twig->render('register.twig', ['title' => 'Register']);
        break;

    case 'dashboard':
        echo $twig->render('dashboard.twig', [
            'title' => 'Dashboard',
            'tickets' => $tickets
        ]);
        break;

    case 'tickets':
        echo $twig->render('ticket-list.twig', [
            'title' => 'Tickets',
            'tickets' => $tickets
        ]);
        break;

    case 'logout':
        echo "
        <script>
          localStorage.removeItem('user');
          localStorage.removeItem('tickets');
          window.location.href='?page=login';
        </script>";
        break;

    default:
        echo $twig->render('login.twig', ['title' => 'Login']);
        break;
}
