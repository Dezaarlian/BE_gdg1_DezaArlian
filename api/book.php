<?php

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'];
$eventId = $data['event_id'];
$qty = $data['quantity'];

if ($qty <= 0) {
    echo json_encode(['message' => 'Quantity must be positive']);
    exit;
}

try {
    $pdo->beginTransaction();

    // GATEKEEPER LOGIC 
    $updateSql = "UPDATE events 
                  SET available_seats = available_seats - ? 
                  WHERE id = ? AND available_seats >= ?";
    
    $stmtUpdate = $pdo->prepare($updateSql);
    $stmtUpdate->execute([$qty, $eventId, $qty]);

    if ($stmtUpdate->rowCount() == 0) {
        // Jika tidak ada baris yang terupdate, berarti stok habis atau event tidak ada
        $pdo->rollBack();
        http_response_code(409); // Conflict
        echo json_encode(['status' => 'failed', 'message' => 'Tiket habis atau tidak cukup!']);
        exit;
    }

    // Ambil data even
    $stmtEvent = $pdo->prepare("SELECT price FROM events WHERE id = ?");
    $stmtEvent->execute([$eventId]);
    $event = $stmtEvent->fetch();
    $totalPrice = $event['price'] * $qty;

    // Insert ke Transaksi
    $bookingCode = 'BOOK-' . time() . '-' . rand(100, 999);
    $insertSql = "INSERT INTO transactions (user_id, event_id, quantity, total_price, booking_code) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $pdo->prepare($insertSql);
    $stmtInsert->execute([$userId, $eventId, $qty, $totalPrice, $bookingCode]);

    // comit
    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'Booking berhasil!',
        'booking_code' => $bookingCode
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>