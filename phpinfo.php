<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

requireAuth();

// Only show in development
$pageTitle = 'PHP Info';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">⚙️ PHP Configuration</span>
            <a href="/index.php" class="btn btn-light btn-sm">← Back to Dashboard</a>
        </div>
    </nav>
    
    <div class="container mb-4">
        <div class="alert alert-warning">
            <strong>⚠️ Security Warning:</strong> This page should be disabled in production!
        </div>
    </div>

    <?php phpinfo(); ?>
</body>
</html>
