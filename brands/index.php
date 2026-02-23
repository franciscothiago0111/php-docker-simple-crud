<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$pageTitle = 'Brands';

// Get all brands
$stmt = $pdo->query("SELECT * FROM brands ORDER BY name");
$brands = $stmt->fetchAll();

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar.php';
?>

<div class="container content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-bookmark"></i> Brands</h1>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Brand
        </a>
    </div>
    
    <?php if (empty($brands)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No brands found. <a href="create.php">Add your first brand</a>!
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($brands as $brand): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($brand['name']) ?></h5>
                        <div class="d-flex gap-2 mt-3">
                            <a href="edit.php?id=<?= $brand['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $brand['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this brand?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
