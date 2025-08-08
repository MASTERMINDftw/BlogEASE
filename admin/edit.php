<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
require_login();

$error = '';
$success = '';

// Get post ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: posts.php');
    exit();
}

// Fetch post data
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: posts.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize_input($_POST['title']);
    $content = sanitize_input($_POST['content']);
    $category = sanitize_input($_POST['category']);
    
    // Handle image upload
    $image_path = $post['image']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = upload_image($_FILES['image']);
        if ($image_path === false) {
            $error = 'Invalid image file. Please upload a valid image (JPG, PNG, GIF) under 5MB.';
        }
    }
    
    // Handle image removal
    if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
        // Delete the image file if it exists
        if (!empty($post['image']) && file_exists('../' . $post['image'])) {
            unlink('../' . $post['image']);
        }
        $image_path = null;
    }
    
    if (empty($title) || empty($content) || empty($category)) {
        $error = 'Please fill in all required fields';
    } elseif (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, category = ?, image = ? WHERE id = ?");
            $stmt->execute([$title, $content, $category, $image_path, $id]);
            $success = 'Post updated successfully!';
            
            // Refresh post data
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = 'Error updating post: ' . $e->getMessage();
        }
    }
}
?>

<div class="admin-header">
    <div class="container header-content">
        <div class="logo">Edit Post</div>
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
        <h2>Edit Post</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Technology" <?php echo $post['category'] == 'Technology' ? 'selected' : ''; ?>>Technology</option>
                    <option value="Design" <?php echo $post['category'] == 'Design' ? 'selected' : ''; ?>>Design</option>
                    <option value="Business" <?php echo $post['category'] == 'Business' ? 'selected' : ''; ?>>Business</option>
                    <option value="Lifestyle" <?php echo $post['category'] == 'Lifestyle' ? 'selected' : ''; ?>>Lifestyle</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Current Image</label>
                <?php if (!empty($post['image']) && file_exists('../' . $post['image'])): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="../<?php echo htmlspecialchars($post['image']); ?>" alt="Current image" style="max-width: 200px; max-height: 150px;">
                    </div>
                    <label>
  Remove current image :
  <input type="checkbox" name="remove_image" value="1" style="width: 14px; margin-inline: 5px; vertical-align: baseline; position: relative; top: 2px;">
</label>

                <?php else: ?>
                    <p>No image uploaded</p>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="image">Upload New Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="button-container" style="text-align: center;">
            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="posts.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<!-- <div class="button-container" style="text-align: center;">
    <button style="height: 54.39px;" type="submit" class="btn btn-primary">Login</button>
    <a href="../index.php" class="btn btn-secondary">Back to Blog</a>
</div> -->