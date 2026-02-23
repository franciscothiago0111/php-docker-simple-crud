<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/functions.php';

requireAuth();

$userId = getCurrentUserId();
$pageTitle = 'Dashboard';

// Get statistics
$stmt = $pdo->prepare("SELECT COUNT(*) FROM cars WHERE user_id = ?");
$stmt->execute([$userId]);
$totalCars = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM brands");
$totalBrands = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM categories");
$totalCategories = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(price) FROM cars WHERE user_id = ?");
$stmt->execute([$userId]);
$totalValue = $stmt->fetchColumn() ?? 0;

// Get recent cars
$stmt = $pdo->prepare("
    SELECT c.*, b.name AS brand_name, cat.name AS category_name 
    FROM cars c
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
    LIMIT 5
");
$stmt->execute([$userId]);
$recentCars = $stmt->fetchAll();

require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/navbar.php';
?>

<div class="container content">
    <h1 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">My Cars</h6>
                            <h2 class="card-title mb-0"><?= $totalCars ?></h2>
                        </div>
                        <i class="bi bi-car-front-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Value</h6>
                            <h2 class="card-title mb-0">$<?= number_format($totalValue, 0) ?></h2>
                        </div>
                        <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Brands</h6>
                            <h2 class="card-title mb-0"><?= $totalBrands ?></h2>
                        </div>
                        <i class="bi bi-bookmark-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Categories</h6>
                            <h2 class="card-title mb-0"><?= $totalCategories ?></h2>
                        </div>
                        <i class="bi bi-tag-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Cars -->
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Cars</h5>
            <a href="/cars/index.php" class="btn btn-sm btn-light">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentCars)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-car-front text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">No cars added yet.</p>
                    <a href="/cars/create.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Your First Car
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentCars as $car): ?>
                            <tr>
                                <td><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($car['model']) ?></td>
                                <td><?= htmlspecialchars($car['year']) ?></td>
                                <td><?= htmlspecialchars($car['category_name'] ?? 'N/A') ?></td>
                                <td>$<?= number_format($car['price'], 2) ?></td>
                                <td>
                                    <a href="/cars/edit.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Quick Actions</h5>
        </div>
        <div class="col-md-4 mb-3">
            <a href="/cars/create.php" class="card text-decoration-none hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-plus-circle text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-dark">Add New Car</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="/brands/create.php" class="card text-decoration-none hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-bookmark-plus text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-dark">Add New Brand</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="/categories/create.php" class="card text-decoration-none hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-tag-fill text-info" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-dark">Add New Category</h5>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transition: box-shadow 0.3s ease-in-out;
    }
</style>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
