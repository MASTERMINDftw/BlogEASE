<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
require_login();

// Get statistics
$total_posts_stmt = $pdo->query("SELECT COUNT(*) FROM posts");
$total_posts = $total_posts_stmt->fetchColumn();

$recent_posts_stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_posts = $recent_posts_stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="admin-header">
    <div class="container header-content">
        <div class="logo">Admin Dashboard</div>
        <div class="user-info">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</div>

<div class="admin-container">
    <div class="admin-sidebar">
        <ul>
            <li class="active"><a href="dashboard.php">Dashboard</a></li>
            <li><a href="posts.php">Manage Posts</a></li>
            <li><a href="create.php">Create Post</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <h2>Dashboard</h2>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Posts</h3>
                <p class="stat-value"><?php echo $total_posts; ?></p>
            </div>
            <div class="stat-card">
                <h3>Recent Activity</h3>
                <p class="stat-value">5</p>
            </div>
        </div>
        
        <div class="recent-posts-section">
            <h3>Recent Posts</h3>
            <table class="posts-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_posts)): ?>
                        <tr>
                            <td colspan="3">No posts found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['category']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<?php require_once '../includes/footer.php'; ?>