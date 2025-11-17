<?php
session_start();
require_once '../db_connect.php';

if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){
    header('Location: login_admin.php'); 
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if(!$product){
    die("Product not found.");
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $image = $_POST['image'] ?? null;
    $category = $_POST['category'];

    $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, image=?, category=? WHERE id=?");
    $stmt->execute([$name,$price,$image,$category,$id]);

    header("Location: products.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Product</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="container">
  <h2>Edit Product</h2>
  <p><a href="products.php">← Back to Products</a></p>

  <form method="post">
    <input class="form-control" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required> <br><br>
    <input class="form-control" type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required> <br><br>
    <input class="form-control" name="image" value="<?php echo htmlspecialchars($product['image']); ?>"> <br><br>

    <select class="form-control" name="category" required>
      <option value="Coffee" <?php if($product['category']=='Coffee') echo 'selected'; ?>>Coffee</option>
      <option value="Pastries" <?php if($product['category']=='Pastries') echo 'selected'; ?>>Pastries</option>
      <option value="Drinks" <?php if($product['category']=='Drinks') echo 'selected'; ?>>Drinks</option>
      <option value="Sandwiches" <?php if($product['category']=='Sandwiches') echo 'selected'; ?>>Sandwiches</option>
      <option value="Desserts" <?php if($product['category']=='Desserts') echo 'selected'; ?>>Desserts</option>
    </select> <br><br>

    <button class="btn" type="submit">Update</button>
  </form>
</div>
</body>
</html>
