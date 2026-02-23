<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$pageTitle = 'Add New Brand';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Brand name is required.';
    }
    
    // Check if brand already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM brands WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->fetch()) {
            $errors[] = 'Brand already exists.';
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO brands (name) VALUES (?)");
            $stmt->execute([$name]);
            
            setFlash('success', 'Brand added successfully!');
            redirect('/brands/index.php');
        } catch(PDOException $e) {
            $errors[] = 'Failed to add brand. Please try again.';
        }
    }
}

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar.php';
?>

<div class="container content">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Brand</h4>
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
                            <label class="form-label">Brand Name *</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required autofocus>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Brand
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
