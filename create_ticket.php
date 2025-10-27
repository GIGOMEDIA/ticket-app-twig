<?php
// Path to tickets JSON
$ticketsFile = __DIR__ . '/tickets.json';

// Make sure JSON file exists
if (!file_exists($ticketsFile)) {
    file_put_contents($ticketsFile, json_encode([]));
}

// Get form data
$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$description = $_POST['description'] ?? '';

if ($title && $category && $description) {
    // Load existing tickets
    $tickets = json_decode(file_get_contents($ticketsFile), true);

    // Add new ticket
    $tickets[] = [
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Save back to JSON
    file_put_contents($ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

    // Redirect to ticket list
    header('Location: index.php?page=tickets');
    exit;
} else {
    die('❌ All fields are required!');
}
