<?php
// Find and display duplicate plate numbers
require_once __DIR__ . '/config/database.php';

echo "🔍 Checking for duplicate plate numbers...\n\n";

try {
    // Find duplicates
    $stmt = $pdo->query("
        SELECT plate_number, COUNT(*) as count,
               GROUP_CONCAT(id) as car_ids,
               GROUP_CONCAT(model) as models
        FROM cars 
        GROUP BY plate_number 
        HAVING count > 1
        ORDER BY count DESC
    ");
    $duplicates = $stmt->fetchAll();
    
    if (empty($duplicates)) {
        echo "✓ No duplicate plate numbers found! Database is clean.\n";
    } else {
        echo "⚠ Found " . count($duplicates) . " duplicate plate number(s):\n\n";
        
        foreach ($duplicates as $dup) {
            echo "Plate: {$dup['plate_number']}\n";
            echo "  Used by {$dup['count']} cars (IDs: {$dup['car_ids']})\n";
            echo "  Models: {$dup['models']}\n";
            
            // Show details for each car
            $ids = explode(',', $dup['car_ids']);
            $stmt = $pdo->prepare("
                SELECT c.id, c.model, c.year, c.color, c.created_at, 
                       b.name as brand_name, u.name as owner_name
                FROM cars c
                LEFT JOIN brands b ON c.brand_id = b.id
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.id IN (" . implode(',', $ids) . ")
            ");
            $stmt->execute();
            $cars = $stmt->fetchAll();
            
            foreach ($cars as $car) {
                echo "    - ID {$car['id']}: {$car['brand_name']} {$car['model']} ({$car['year']}) - Owner: {$car['owner_name']} - Created: {$car['created_at']}\n";
            }
            echo "\n";
        }
        
        echo "---\n";
        echo "To fix duplicates, you can:\n";
        echo "1. Delete duplicate cars: http://localhost:8080/cars/index.php\n";
        echo "2. Edit plate numbers: http://localhost:8080/cars/edit.php?id=<car_id>\n";
        echo "3. Use phpMyAdmin: http://localhost:8081\n";
        echo "\nAfter fixing, run: php migrate-plate-unique.php\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
