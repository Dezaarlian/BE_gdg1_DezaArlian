<?php
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

// all
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM events");
    $events = $stmt->fetchAll();
    echo json_encode(['data' => $events]);
}

// event
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO events (title, description, event_date, capacity, available_seats, price) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            $data['title'], 
            $data['description'] ?? '', 
            $data['event_date'], 
            $data['capacity'], 
            $data['capacity'], // Awalnya available = capacity
            $data['price']
        ]);
        http_response_code(201);
        echo json_encode(['message' => 'Event created successfully']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>