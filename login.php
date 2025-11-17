<?php include 'header.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([$email]);
  $user = $stmt->fetch();
  if($user && password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['is_admin'] = (bool)$user['is_admin'];
    header('Location: index.php'); exit;
  } else {
    $errors[] = 'Invalid login.';
  }
}
?>
<h2>Login</h2>
<?php foreach($errors as $e) echo '<p class="card">'.$e.'</p>'; ?>
<form method="post">
  <input class="form-control" name="email" type="email" placeholder="Email" required><br><br>
  <input class="form-control" name="password" type="password" placeholder="Password" required><br><br>
  <button class="btn" type="submit">Login</button>
</form>
<p><a href="index.php">Back</a></p>
<?php include 'footer.php'; ?>
