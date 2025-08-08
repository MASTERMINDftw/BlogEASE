
<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Get post ID from URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$post_id) {
    header('Location: index.php');
    exit();
}

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: index.php');
    exit();
}

// Fetch recent posts for sidebar
$stmt = $pdo->query("SELECT id, title FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<header>
    <div class="container header-content">
        <div class="logo">BlogEASE</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="admin/login.php">Admin</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container main-content">
    <div class="blog-posts">
        <div class="post-card">
            <?php if (!empty($post['image']) && file_exists($post['image'])): ?>
                <div class="post-image">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
            <?php else: ?>
                <div class="post-image">Featured Image</div>
            <?php endif; ?>
            <div class="post-content">
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-meta">
                    <span>Category: <?php echo htmlspecialchars($post['category']); ?></span> | 
                    <span>Date: <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                </div>
                <div class="post-full-content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
                <a href="index.php" class="read-more">← Back to Blog</a>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <div class="sidebar-widget">
            <h3 class="widget-title">Recent Posts</h3>
            <ul class="recent-posts">
                <?php if (empty($recent_posts)): ?>
                    <li>No recent posts</li>
                <?php else: ?>
                    <?php foreach ($recent_posts as $recent_post): ?>
                        <li><a href="post.php?id=<?php echo $recent_post['id']; ?>"><?php echo htmlspecialchars($recent_post['title']); ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>