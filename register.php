<?php
require_once 'config.php';

// If already logged in, redirect to homepage
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Password confirmation does not match.';
    } else {
        try {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username already exists.';
            } else {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Email is already registered.';
                } else {
                    // Create new user
                    $hashed_password = hash_password($password);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $email, $hashed_password]);
                    
                    $success = 'Registration successful! Please log in to your account.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Registration failed, please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - <?php echo htmlspecialchars(get_site_setting('site_title')); ?></title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>User Registration</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
            </form>
            
            <div style="text-align: center; margin-top: 1rem;">
                <p><a href="login.php">Already have an account? Log in here.</a></p>
                <p><a href="index.php">Back to Homepage</a></p>
            </div>
        </div>
    </div>
</body>
</html>