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
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? AND status = 'active'");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && verify_password($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                redirect('index.php');
            } else {
                $error = 'Incorrect username or password.';
            }
        } catch (PDOException $e) {
            $error = 'Login failed, please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - <?php echo htmlspecialchars(get_site_setting('site_title')); ?></title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>User Login</h2>
            
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
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <div style="text-align: center; margin-top: 1rem;">
                <p><a href="register.php">Don't have an account yet? Register now</a></p>
                <p><a href="index.php">Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>