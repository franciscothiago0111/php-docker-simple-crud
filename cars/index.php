<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$userId = getCurrentUserId();
$pageTitle = 'My Cars';

// Get user's cars with brand and category info
$stmt = $pdo->prepare("
    SELECT c.*, b.name AS brand_name, cat.name AS category_name 
    FROM cars c
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$userId]);
$cars = $stmt->fetchAll();

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar.php';
?>

<div class="container content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-car-front"></i> My Cars</h1>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Car
        </a>
    </div>
    
    <?php if (empty($cars)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> You don't have any cars yet. <a href="create.php">Add your first car</a>!
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Plate</th>
                        <th>Color</th>
                        <th>Mileage</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cars as $car): ?>
                    <tr>
                        <td><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($car['model']) ?></td>
                        <td><?= htmlspecialchars($car['year']) ?></td>
                        <td><?= htmlspecialchars($car['plate_number']) ?></td>
                        <td>
                            <span class="badge" style="background-color: <?= htmlspecialchars($car['color']) ?>; color: white;">
                                <?= htmlspecialchars($car['color']) ?>
                            </span>
                        </td>
                        <td><?= number_format($car['mileage']) ?> km</td>
                        <td>$<?= number_format($car['price'], 2) ?></td>
                        <td><?= htmlspecialchars($car['category_name'] ?? 'N/A') ?></td>
                        <td>
                            <a href="edit.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this car?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
