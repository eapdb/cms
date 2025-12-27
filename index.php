<?php
require_once 'config.php';

// Get article list (only show published articles)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

try {
    // Get total number of articles
    $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE status = 'published'");
    $total_stmt->execute();
    $total_articles = $total_stmt->fetchColumn();
    $total_pages = ceil($total_articles / $limit);

    // Get current page articles
    $stmt = $pdo->prepare("
        SELECT a.*, au.username as author_name 
        FROM articles a 
        LEFT JOIN admin_users au ON a.author_id = au.id 
        WHERE a.status = 'published' 
        ORDER BY a.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$limit, $offset]);
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Failed to retrieve articles: " . $e->getMessage();
}

$site_title = get_site_setting('site_title');
$site_description = get_site_setting('site_description');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($site_description); ?>">
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo"><?php echo htmlspecialchars($site_title); ?></a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="article.php">Articles</a></li>
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
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    Welcome to <?php echo htmlspecialchars($site_title); ?>
                </div>
                <div class="card-body">
                    <p><?php echo htmlspecialchars($site_description); ?></p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Latest Articles
                </div>
                <div class="card-body">
                    <?php if (empty($articles)): ?>
                        <p>No articles found.</p>
                    <?php else: ?>
                        <div class="article-list">
                            <?php foreach ($articles as $article): ?>
                                <div class="article-item">
                                    <h3><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
                                    <div class="meta">
                                        Author: <?php echo htmlspecialchars($article['author_name'] ?? 'Unknown'); ?> | 
                                        Published: <?php echo date('Y-m-d H:i', strtotime($article['created_at'])); ?>
                                    </div>
                                    <div class="excerpt">
                                        <?php 
                                        $content = strip_tags($article['content']);
                                        echo htmlspecialchars(mb_substr($content, 0, 200)) . (mb_strlen($content) > 200 ? '...' : '');
                                        ?>
                                    </div>
                                    <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">Read More</a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>">Previous</a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>>
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>">Next</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
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