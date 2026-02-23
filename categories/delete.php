<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$categoryId = $_GET['id'] ?? null;

if (!$categoryId) {
    setFlash('error', 'Invalid category ID.');
    redirect('/categories/index.php');
}

try {
    // Check if category is used by any cars
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cars WHERE category_id = ?");
    $stmt->execute([$categoryId]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        setFlash('error', 'Cannot delete category. It is used by ' . $count . ' car(s).');
    } else {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);
        
        if ($stmt->rowCount() > 0) {
            setFlash('success', 'Category deleted successfully!');
        } else {
            setFlash('error', 'Category not found.');
        }
    }
} catch(PDOException $e) {
    setFlash('error', 'Failed to delete category. Please try again.');
}

redirect('/categories/index.php');
