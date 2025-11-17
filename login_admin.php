<?php
session_start();
require_once '../db_connect.php';w

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1 LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Check password (hashed or plain fallback)
        if (password_verify($password, $admin['password']) || $password === $admin['password']) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['name'];
            $_SESSION['is_admin'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        $errors[] = "Admin not found or not authorized.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - 3M's Cafe</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="container">
  <h2>Admin Login</h2>
  <?php if ($errors): ?>
    <?php foreach ($errors as $e): ?>
      <p class="card" style="color:red;"><?php echo htmlspecialchars($e); ?></p>
    <?php endforeach; ?>
  <?php endif; ?>
  <form method="post">
    <div class="form-group">
      <input class="form-control" type="email" name="email" placeholder="Admin Email" required>
    </div>
    <div class="form-group">
      <input class="form-control" type="password" name="password" placeholder="Password" required>
    </div>
    <button class="btn" type="submit">Login</button>
  </form>
  <p class="back-to-menu"><a href="../index.php">Back to Menu</a></p>
</div>
</body>
</html>
