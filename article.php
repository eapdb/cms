<?php
require_once 'config.php';

// Get article ID
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$article_id) {
    redirect('index.php');
}

try {
    // Get article content
    $stmt = $pdo->prepare("
        SELECT a.*, au.username as author_name 
        FROM articles a 
        LEFT JOIN admin_users au ON a.author_id = au.id 
        WHERE a.id = ? AND a.status = 'published'
    ");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();
    
    if (!$article) {
        redirect('index.php');
    }
} catch (PDOException $e) {
    redirect('index.php');
}

$site_title = get_site_setting('site_title');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - <?php echo htmlspecialchars($site_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(mb_substr(strip_tags($article['content']), 0, 160)); ?>">
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo"><?php echo htmlspecialchars($site_title); ?></a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="articles.php">Articles</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li><a href="admin/login.php">Admin Panel</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <article class="card">
                <div class="card-body">
                    <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                    
                    <div class="meta" style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #eee; color: #666;">
                        Author: <?php echo htmlspecialchars($article['author_name'] ?? 'Unknown'); ?> | 
                        Published: <?php echo date('Y-m-d H:i', strtotime($article['created_at'])); ?>
                        <?php if ($article['updated_at'] != $article['created_at']): ?>
                            | Updated: <?php echo date('Y-m-d H:i', strtotime($article['updated_at'])); ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="article-content" style="line-height: 1.8; font-size: 1.1rem;">
                        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                    </div>
                </div>
            </article>

            <div style="margin-top: 2rem; text-align: center;">
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
                <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_title); ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>