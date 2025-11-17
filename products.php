<?php
session_start();
require_once '../db_connect.php';
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){ header('Location: login_admin.php'); exit; }

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $image = $_POST['image'] ?? null;
    $category = $_POST['category'];

    $stmt = $pdo->prepare("INSERT INTO products (name,price,image,category) VALUES (?,?,?,?)");
    $stmt->execute([$name,$price,$image,$category]);

    header("Location: products.php"); exit;
}
$products=$pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Products</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="container">
  <h2>Manage Products</h2>
  <p><a href="dashboard.php">← Back to Dashboard</a></p>

  <h3>Add Product</h3>
  <form method="post">
    <input class="form-control" name="name" placeholder="Name" required> <br><br>
    <input class="form-control" type="number" step="0.01" name="price" placeholder="Price" required> <br><br>
    <input class="form-control" name="image" placeholder="Image path e.g. images/latte.jpg"> <br><br>
    
    <select class="form-control" name="category" required>
      <option value="">-- Select Category --</option>
      <option value="Coffee">Coffee</option>
      <option value="Pastries">Pastries</option>
      <option value="Drinks">Drinks</option>
      <option value="Sandwiches">Sandwiches</option>
      <option value="Desserts">Desserts</option>
    </select> <br><br>

    <button class="btn" type="submit">Add</button> <br><br>
  </form>

  <h3>Products List</h3>
  <?php foreach($products as $p): ?>
    <div class="card">
      <strong><?php echo htmlspecialchars($p['name']); ?></strong> ₱<?php echo $p['price']; ?><br>
      <em>Category: <?php echo htmlspecialchars($p['category']); ?></em><br><br>
      <img src="../<?php echo $p['image'] ?: 'images/placeholder.jpg'; ?>" width="100"><br>
      <a href="product_edit.php?id=<?php echo $p['id']; ?>">Edit</a> |
      <a href="product_delete.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>
