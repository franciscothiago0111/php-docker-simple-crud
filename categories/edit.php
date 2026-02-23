<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$categoryId = $_GET['id'] ?? null;
$pageTitle = 'Edit Category';
$errors = [];

if (!$categoryId) {
    setFlash('error', 'Invalid category ID.');
    redirect('/categories/index.php');
}

// Get category
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch();

if (!$category) {
    setFlash('error', 'Category not found.');
    redirect('/categories/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Category name is required.';
    }
    
    // Check if category name already exists (excluding current category)
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND id != ?");
        $stmt->execute([$name, $categoryId]);
        if ($stmt->fetch()) {
            $errors[] = 'Category name already exists.';
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description ?: null, $categoryId]);
            
            setFlash('success', 'Category updated successfully!');
            redirect('/categories/index.php');
        } catch(PDOException $e) {
            $errors[] = 'Failed to update category. Please try again.';
        }
    }
} else {
    $_POST = $category;
}

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar.php';
?>

<div class="container content">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Category</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Category Name *</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Update Category
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
