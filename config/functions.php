<?php
// Set flash message
function setFlash($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

// Get and clear flash message
function getFlash() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'info';
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_type'], $_SESSION['flash_message']);
        return ['type' => $type, 'message' => $message];
    }
    return null;
}

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Redirect helper
function redirect($path) {
    header("Location: $path");
    exit();
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
