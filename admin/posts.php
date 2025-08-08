<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
require_login();

// Handle delete request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: posts.php?deleted=true');
    exit();
}

// Fetch all posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-header">
    <div class="container header-content">
        <div class="logo">Manage Posts</div>
        <div class="user-info">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</div>

<div class="admin-container">
    <div class="admin-sidebar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active"><a href="posts.php">Manage Posts</a></li>
            <li><a href="create.php">Create Post</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <h2>Manage Posts</h2>
        
        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
            <div class="success-message">Post deleted successfully!</div>
        <?php endif; ?>
        
        <table class="posts-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="4">No posts found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['category']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $post['id']; ?>" class="action-btn edit-btn">Edit</a>
                                <a href="posts.php?delete=<?php echo $post['id']; ?>" 
                                   class="action-btn delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>