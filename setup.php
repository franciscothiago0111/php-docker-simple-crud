<?php
// Database setup script
require_once __DIR__ . '/config/database.php';

echo "Setting up database...\n\n";

try {
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/database/init.sql');
    
    // Split SQL statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "✓ Database tables created successfully!\n";
    echo "✓ Sample brands and categories inserted!\n";
    echo "✓ Demo user created (email: demo@example.com, password: demo123)\n\n";
    echo "Setup completed successfully!\n";
    echo "You can now access the application at: http://localhost:8080\n\n";
    
} catch (PDOException $e) {
    echo "✗ Error setting up database: " . $e->getMessage() . "\n";
    exit(1);
}
