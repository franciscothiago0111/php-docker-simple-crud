<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

requireAuth();

$errorLog = __DIR__ . '/logs/error.log';
$apacheErrorLog = '/var/log/apache2/error.log';
$apacheAccessLog = '/var/log/apache2/access.log';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Logs - Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1e1e1e; color: #d4d4d4; }
        .log-container { 
            background: #2d2d2d; 
            padding: 20px; 
            border-radius: 8px; 
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            max-height: 500px;
            overflow-y: auto;
        }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .success { color: #4ec9b0; }
        .navbar-custom { background: #007acc; }
        pre { margin: 0; white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom navbar-dark mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🔍 Error Logs Viewer</span>
            <a href="/index.php" class="btn btn-light btn-sm">← Back to Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <!-- PHP Application Error Log -->
        <div class="card bg-dark mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">📋 PHP Application Errors</h5>
            </div>
            <div class="card-body">
                <div class="log-container">
                    <pre><?php
                    if (file_exists($errorLog)) {
                        $content = file_get_contents($errorLog);
                        if (empty(trim($content))) {
                            echo '<span class="success">✓ No errors logged yet. Application is running clean!</span>';
                        } else {
                            echo htmlspecialchars($content);
                        }
                    } else {
                        echo '<span class="warning">⚠ Log file not created yet. Will be created on first error.</span>';
                    }
                    ?></pre>
                </div>
                <?php if (file_exists($errorLog)): ?>
                    <div class="mt-3">
                        <a href="?clear=php" class="btn btn-sm btn-warning" onclick="return confirm('Clear PHP error log?')">
                            Clear Log
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Apache Error Log -->
        <div class="card bg-dark mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">⚙️ Apache Error Log (Last 50 lines)</h5>
            </div>
            <div class="card-body">
                <div class="log-container">
                    <pre><?php
                    if (file_exists($apacheErrorLog)) {
                        $lines = file($apacheErrorLog);
                        $lastLines = array_slice($lines, -50);
                        echo htmlspecialchars(implode('', $lastLines));
                    } else {
                        echo '<span class="warning">⚠ Apache error log not accessible from PHP</span>';
                    }
                    ?></pre>
                </div>
            </div>
        </div>

        <!-- Apache Access Log -->
        <div class="card bg-dark mb-4">
            <div class="card-header bg-info text-dark">
                <h5 class="mb-0">📊 Apache Access Log (Last 30 requests)</h5>
            </div>
            <div class="card-body">
                <div class="log-container">
                    <pre><?php
                    if (file_exists($apacheAccessLog)) {
                        $lines = file($apacheAccessLog);
                        $lastLines = array_slice($lines, -30);
                        echo htmlspecialchars(implode('', $lastLines));
                    } else {
                        echo '<span class="warning">⚠ Apache access log not accessible from PHP</span>';
                    }
                    ?></pre>
                </div>
            </div>
        </div>

        <!-- Quick Commands -->
        <div class="card bg-dark mb-4">
            <div class="card-header bg-secondary">
                <h5 class="mb-0">🛠️ Quick Debug Commands</h5>
            </div>
            <div class="card-body text-white">
                <p class="mb-2"><strong>View real-time Apache errors:</strong></p>
                <code class="d-block bg-black p-2 mb-3">docker exec -it php_apache_container tail -f /var/log/apache2/error.log</code>
                
                <p class="mb-2"><strong>View real-time access log:</strong></p>
                <code class="d-block bg-black p-2 mb-3">docker exec -it php_apache_container tail -f /var/log/apache2/access.log</code>
                
                <p class="mb-2"><strong>View container logs:</strong></p>
                <code class="d-block bg-black p-2 mb-3">docker logs -f php_apache_container</code>
                
                <p class="mb-2"><strong>Enter container:</strong></p>
                <code class="d-block bg-black p-2 mb-3">docker exec -it php_apache_container bash</code>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Handle log clearing
if (isset($_GET['clear']) && $_GET['clear'] === 'php') {
    if (file_exists($errorLog)) {
        file_put_contents($errorLog, '');
        header('Location: view-logs.php');
        exit();
    }
}
?>
