<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

requireAuth();

$userId = getCurrentUserId();
$pageTitle = 'Add New Car';
$errors = [];

// Get brands and categories for dropdowns
$brandsStmt = $pdo->query("SELECT id, name FROM brands ORDER BY name");
$brands = $brandsStmt->fetchAll();

$categoriesStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
$categories = $categoriesStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandId = $_POST['brand_id'] ?? '';
    $categoryId = $_POST['category_id'] ?? '';
    $model = sanitize($_POST['model'] ?? '');
    $year = $_POST['year'] ?? '';
    $plateNumber = sanitize($_POST['plate_number'] ?? '');
    $color = sanitize($_POST['color'] ?? '');
    $mileage = $_POST['mileage'] ?? '';
    $price = $_POST['price'] ?? '';
    
    // Validation
    if (empty($brandId)) $errors[] = 'Brand is required.';
    if (empty($categoryId)) $errors[] = 'Category is required.';
    if (empty($model)) $errors[] = 'Model is required.';
    if (empty($year) || !is_numeric($year) || $year < 1900 || $year > date('Y') + 1) {
        $errors[] = 'Valid year is required.';
    }
    if (empty($plateNumber)) {
        $errors[] = 'Plate number is required.';
    } else {
        // Check if plate number already exists
        $stmt = $pdo->prepare("SELECT id FROM cars WHERE plate_number = ?");
        $stmt->execute([$plateNumber]);
        if ($stmt->fetch()) {
            $errors[] = 'This plate number is already registered in the system.';
        }
    }
    
    if (empty($color)) $errors[] = 'Color is required.';
    if (empty($mileage) || !is_numeric($mileage) || $mileage < 0) {
        $errors[] = 'Valid mileage is required.';
    }
    if (empty($price) || !is_numeric($price) || $price < 0) {
        $errors[] = 'Valid price is required.';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO cars (user_id, brand_id, category_id, model, year, plate_number, color, mileage, price)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $brandId, $categoryId, $model, $year, $plateNumber, $color, $mileage, $price]);
            
            setFlash('success', 'Car added successfully!');
            redirect('/cars/index.php');
        } catch(PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
            error_log('Car creation error: ' . $e->getMessage());
        }
    }
}

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar.php';
?>

<div class="container content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Car</h4>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand *</label>
                                <select name="brand_id" class="form-select" required>
                                    <option value="">Select Brand</option>
                                    <?php foreach($brands as $brand): ?>
                                        <option value="<?= $brand['id'] ?>" <?= isset($_POST['brand_id']) && $_POST['brand_id'] == $brand['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($brand['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model *</label>
                                <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($_POST['model'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= isset($_POST['category_id']) && $_POST['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Year *</label>
                                <input type="number" name="year" class="form-control" value="<?= htmlspecialchars($_POST['year'] ?? '') ?>" min="1900" max="<?= date('Y') + 1 ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plate Number *</label>
                                <input type="text" name="plate_number" class="form-control" value="<?= htmlspecialchars($_POST['plate_number'] ?? '') ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color *</label>
                                <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($_POST['color'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mileage (km) *</label>
                                <input type="number" name="mileage" class="form-control" value="<?= htmlspecialchars($_POST['mileage'] ?? '') ?>" min="0" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price ($) *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" min="0" required>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Car
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
