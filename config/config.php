<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'bms');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'GLMSphp');
define('APP_URL', 'http://localhost/GLMSphp');
define('APP_VERSION', '1.0.0');

// Security constants
define('HASH_COST', 12); // For password hashing
define('TOKEN_EXPIRY', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['pdf', 'jpg', 'jpeg', 'png']);
define('UPLOAD_PATH', __DIR__ . '/../uploads');

// Session configuration
session_start();
session_regenerate_id(true);

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to validate CSRF token
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Function to generate CSRF token field
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Database connection with error handling
function get_db_connection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

// Error handling function
function handle_error($message, $severity = 'ERROR') {
    $log_message = date('Y-m-d H:i:s') . " [$severity] $message\n";
    error_log($log_message, 3, __DIR__ . '/../logs/error.log');
    
    if ($severity === 'FATAL') {
        die("An error occurred. Please try again later.");
    }
}

// Input sanitization function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Password hashing function
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
}

// Password verification function
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// File upload validation
function validate_file_upload($file) {
    $errors = [];
    
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = "File size exceeds limit";
    }
    
    $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_type, ALLOWED_FILE_TYPES)) {
        $errors[] = "File type not allowed";
    }
    
    return $errors;
} 