<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$userId = getCurrentUserId();
$carId = $_GET['id'] ?? null;

if (!$carId) {
    setFlash('error', 'Invalid car ID.');
    redirect('/cars/index.php');
}

try {
    // Verify ownership before deleting
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ? AND user_id = ?");
    $stmt->execute([$carId, $userId]);
    
    if ($stmt->rowCount() > 0) {
        setFlash('success', 'Car deleted successfully!');
    } else {
        setFlash('error', 'Car not found or you do not have permission to delete it.');
    }
} catch(PDOException $e) {
    setFlash('error', 'Failed to delete car. Please try again.');
}

redirect('/cars/index.php');
