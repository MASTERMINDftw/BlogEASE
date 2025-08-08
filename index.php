<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Get category filter from URL
$category_filter = isset($_GET['category']) ? sanitize_input($_GET['category']) : '';

// Fetch posts based on category filter
if ($category_filter) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE category = ? ORDER BY created_at DESC");
    $stmt->execute([$category_filter]);
    $page_title = $category_filter . " Posts";
} else {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    $page_title = "All Posts";
}
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent posts for sidebar
$stmt = $pdo->query("SELECT id, title FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all unique categories for sidebar
$stmt = $pdo->query("SELECT DISTINCT category FROM posts ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h2 style="margin-bottom: 35px;margin-top: -10px;"><?php echo $category_filter ? htmlspecialchars($category_filter) . ' Posts' : 'All Posts'; ?></h2>
        <?php if (empty($posts)): ?>
            <p>No posts available<?php echo $category_filter ? ' in the ' . htmlspecialchars($category_filter) . ' category' : ''; ?>.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <?php if (!empty($post['image']) && file_exists($post['image'])): ?>
                        <div class="post-image">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </div>
                    <?php else: ?>
                        <div class="post-image">Featured Image</div>
                    <?php endif; ?>
                    <div class="post-content">
                        <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <div class="post-meta">
                            <span>Category: <?php echo htmlspecialchars($post['category']); ?></span> | 
                            <span>Date: <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        <p class="post-excerpt"><?php echo htmlspecialchars(substr($post['content'], 0, 200)) . '...'; ?></p>
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="sidebar">
        <div class="sidebar-widget" style="margin-top: 63px;">
            <h3 class="widget-title">Recent Posts</h3>
            <ul class="recent-posts">
                <?php if (empty($recent_posts)): ?>
                    <li>No recent posts</li>
                <?php else: ?>
                    <?php foreach ($recent_posts as $post): ?>
                        <li><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="sidebar-widget">
            <h3 class="widget-title">Categories</h3>
            <ul class="categories">
                <li><a href="index.php"<?php echo !$category_filter ? ' class="active"' : ''; ?>>All Categories</a></li>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="index.php?category=<?php echo urlencode($cat['category']); ?>"<?php echo ($category_filter == $cat['category']) ? ' class="active"' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top">↑</button>
<script>
    // Show button when user scrolls down
    window.onscroll = function () {
        const btn = document.getElementById("scrollTopBtn");
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            btn.style.display = "block";
        } else {
            btn.style.display = "none";
        }
    };

    // Scroll to top smoothly
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>



<?php require_once 'includes/footer.php'; ?>