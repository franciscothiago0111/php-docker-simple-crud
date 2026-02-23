<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$brandId = $_GET['id'] ?? null;

if (!$brandId) {
    setFlash('error', 'Invalid brand ID.');
    redirect('/brands/index.php');
}

try {
    // Check if brand is used by any cars
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cars WHERE brand_id = ?");
    $stmt->execute([$brandId]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        setFlash('error', 'Cannot delete brand. It is used by ' . $count . ' car(s).');
    } else {
        $stmt = $pdo->prepare("DELETE FROM brands WHERE id = ?");
        $stmt->execute([$brandId]);
        
        if ($stmt->rowCount() > 0) {
            setFlash('success', 'Brand deleted successfully!');
        } else {
            setFlash('error', 'Brand not found.');
        }
    }
} catch(PDOException $e) {
    setFlash('error', 'Failed to delete brand. Please try again.');
}

redirect('/brands/index.php');
