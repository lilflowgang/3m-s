<?php
session_start();
require_once '../db_connect.php';
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){ header('Location: login_admin.php'); exit; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['order_id'],$_POST['status'])){
    $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$_POST['status'],$_POST['order_id']]);
    header("Location: dashboard.php"); exit;
}

$stmt=$pdo->query("SELECT o.*, u.name as customer FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.created_at DESC");
$orders=$stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="container">
  <h2>Admin Dashboard</h2>
  <p>Welcome, <?php echo $_SESSION['user_name']; ?> | <a href="../logout.php">Logout</a></p>

  <nav style="margin:15px 0;">
    <a class="btn" href="dashboard.php">Orders</a>
    <a class="btn" href="products.php">Products</a>
  </nav>

  <?php foreach($orders as $o): ?>
    <div class="card">
      <strong>Order #<?php echo $o['id']; ?> — <?php echo htmlspecialchars($o['customer']); ?></strong>
      <p>Total: ₱<?php echo number_format($o['total'],2); ?> — <?php echo $o['payment_method']; ?> — <?php echo $o['created_at']; ?></p>

      <details>
        <summary>View Items</summary>
        <ul>
        <?php
          $stmt2=$pdo->prepare("SELECT oi.qty,oi.price,p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
          $stmt2->execute([$o['id']]);
          foreach($stmt2->fetchAll() as $it){
            echo "<li>{$it['qty']}x ".htmlspecialchars($it['name'])." ₱".number_format($it['price'],2)."</li>";
          }
        ?>
        </ul>
      </details>

      <form method="post">
        <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
        <select name="status" class="form-control" style="width:auto;display:inline-block;">
          <option <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
          <option <?php if($o['status']=='Paid') echo 'selected'; ?>>Paid</option>
          <option <?php if($o['status']=='Completed') echo 'selected'; ?>>Completed</option>
          <option <?php if($o['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>
        <button class="btn" type="submit">Update</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>
