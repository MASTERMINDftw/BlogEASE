<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
require_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize_input($_POST['title']);
    $content = sanitize_input($_POST['content']);
    $category = sanitize_input($_POST['category']);
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = upload_image($_FILES['image']);
        if ($image_path === false) {
            $error = 'Invalid image file. Please upload a valid image (JPG, PNG, GIF) under 5MB.';
        }
    }
    
    if (empty($title) || empty($content) || empty($category)) {
        $error = 'Please fill in all required fields';
    } elseif (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, category, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $category, $image_path]);
            $success = 'Post created successfully!';
        } catch (PDOException $e) {
            $error = 'Error creating post: ' . $e->getMessage();
        }
    }
}
?>

<div class="admin-header">
    <div class="container header-content">
        <div class="logo">Create Post</div>
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
            <li><a href="posts.php">Manage Posts</a></li>
            <li class="active"><a href="create.php">Create Post</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <h2>Create New Post</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Technology">Technology</option>
                    <option value="Design">Design</option>
                    <option value="Business">Business</option>
                    <option value="Lifestyle">Lifestyle</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Featured Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>
            <div class="button-container" style="text-align: center;">
    <button class="btn btn-primary">Publish Post</button>
    <button class="btn btn-secondary " style="height: 54.38px;">Cancel</button>
</div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>