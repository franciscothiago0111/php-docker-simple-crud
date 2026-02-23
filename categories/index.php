<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$pageTitle = 'Categories';

// Get all categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar.php';
?>

<div class="container content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-tag"></i> Categories</h1>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Category
        </a>
    </div>
    
    <?php if (empty($categories)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No categories found. <a href="create.php">Add your first category</a>!
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($categories as $category): ?>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                        <?php if ($category['description']): ?>
                            <p class="card-text text-muted"><?= htmlspecialchars($category['description']) ?></p>
                        <?php endif; ?>
                        <div class="d-flex gap-2 mt-3">
                            <a href="edit.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this category?')">
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
