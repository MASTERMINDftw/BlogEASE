<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        // Check credentials
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>

<div class="login-container">
    <div class="login-form">
        <h2>Admin Login</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <br><br>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
           <div class="button-container" style="text-align: center;">
    <button style="height: 54.39px;" type="submit" class="btn btn-primary">Login</button>
    <a href="../index.php" class="btn btn-secondary">Back to Blog</a>
</div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>