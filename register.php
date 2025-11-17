<?php include 'header.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if(!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6){
    $errors[] = 'Invalid details.';
  } else {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if($stmt->fetch()){
      $errors[] = 'Email already registered.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)')->execute([$name,$email,$hash]);
      echo '<p class="card">Registered! <br> <a href="login.php">Login</a></p>';
      include 'footer.php'; exit;
    }
  }
}
?>
<h2>Register</h2>
<?php foreach($errors as $e) echo '<p class="card">'.$e.'</p>'; ?>
<form method="post">
  <input class="form-control" name="name" placeholder="Name" required> <br><br>
  <input class="form-control" name="email" type="email" placeholder="Email" required><br><br>
  <input class="form-control" name="password" type="password" placeholder="Password" required><br><br>
  <button class="btn" type="submit">Register</button>
</form>
<p><a href="index.php">Back to Menu</a></p>
<?php include 'footer.php'; ?>
