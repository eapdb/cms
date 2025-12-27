<?php
// Database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cms_system');

// Website settings
define('SITE_URL', 'http://localhost/cms/');
define('ADMIN_URL', SITE_URL . 'admin/');

// Session settings
session_start();

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// General functions
function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

function get_site_setting($key) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : '';
}

// Password hashing function
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Password verification function
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Clean input data
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Time formatting
function format_date($date) {
    return date('Y-m-d H:i:s', strtotime($date));
}
?>