<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'Invalid email format.';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    
    // Authenticate user
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                setFlash('success', 'Welcome back, ' . $user['name'] . '!');
                redirect('/index.php');
            } else {
                $errors[] = 'Invalid email or password.';
            }
        } catch(PDOException $e) {
            // Show actual error in development
            $errors[] = 'Database error: ' . $e->getMessage();
            error_log('Login error: ' . $e->getMessage());
        }
    }
}

$pageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-car-front-fill text-primary" style="font-size: 3rem;"></i>
                    <h2 class="mt-2">Car Manager</h2>
                    <p class="text-muted">Sign in to your account</p>
                </div>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php 
                $flash = getFlash();
                if ($flash): 
                ?>
                    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?>">
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                    
                    <div class="text-center">
                        <p class="text-muted">Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
