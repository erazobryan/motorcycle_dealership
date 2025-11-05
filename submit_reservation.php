<?php
require 'db_con.php';
require_once 'includes/session_check.php';
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'] ?? null;
$motorcycle_id = $_POST['motorcycle_id'] ?? null;
$date = $_POST['date'] ?? '';

if (!$motorcycle_id || !$date || !$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data. Please make sure you are logged in and filled out all fields.']);
    exit;
}

try {
    $reserveDate = date('Y-m-d', strtotime($date));

    $stmt = $pdo->prepare("
        INSERT INTO reservation (Reservation_date, Status, User_User_id, Motorcycle_Motorcycle_id)
        VALUES (:rdate, 'Pending', :uid, :mid)
    ");
    $stmt->execute([
        ':rdate' => $reserveDate,
        ':uid'   => $user_id,
        ':mid'   => $motorcycle_id
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Reservation submitted successfully! Our staff will contact you soon.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}