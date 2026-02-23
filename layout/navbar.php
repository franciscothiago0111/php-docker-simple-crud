<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
$currentUser = getCurrentUser($pdo);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <i class="bi bi-car-front-fill"></i> Car Manager
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php"><i class="bi bi-house-door"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cars/index.php"><i class="bi bi-car-front"></i> Cars</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/brands/index.php"><i class="bi bi-bookmark"></i> Brands</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/categories/index.php"><i class="bi bi-tag"></i> Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/view-logs.php"><i class="bi bi-bug"></i> Debug Logs</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($currentUser['name'] ?? 'User') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
$flash = getFlash();
if ($flash):
    $alertClass = match($flash['type']) {
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        default => 'alert-info'
    };
?>
<div class="container">
    <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
