<?php
// Apply plate_number unique constraint migration
require_once __DIR__ . '/config/database.php';

echo "Applying migration: Add UNIQUE constraint to plate_number...\n\n";

try {
    // Check if constraint already exists
    $stmt = $pdo->query("
        SELECT COUNT(*) as count
        FROM information_schema.TABLE_CONSTRAINTS
        WHERE CONSTRAINT_SCHEMA = 'crud_php'
        AND TABLE_NAME = 'cars'
        AND CONSTRAINT_NAME = 'unique_plate_number'
        AND CONSTRAINT_TYPE = 'UNIQUE'
    ");
    
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        echo "✓ UNIQUE constraint on plate_number already exists. No action needed.\n";
    } else {
        // Check for duplicate plate numbers first
        $stmt = $pdo->query("
            SELECT plate_number, COUNT(*) as count 
            FROM cars 
            GROUP BY plate_number 
            HAVING count > 1
        ");
        $duplicates = $stmt->fetchAll();
        
        if (!empty($duplicates)) {
            echo "⚠ WARNING: Found duplicate plate numbers! Please fix these first:\n\n";
            foreach ($duplicates as $dup) {
                echo "  - Plate: {$dup['plate_number']} (appears {$dup['count']} times)\n";
            }
            echo "\nUnable to add UNIQUE constraint with duplicate values.\n";
            echo "Please update or delete duplicate plate numbers, then run this script again.\n";
            exit(1);
        }
        
        // Add the unique constraint
        $pdo->exec("ALTER TABLE cars ADD UNIQUE KEY unique_plate_number (plate_number)");
        echo "✓ Successfully added UNIQUE constraint to plate_number column!\n";
    }
    
    echo "\nMigration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
