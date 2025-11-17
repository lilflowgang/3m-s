<?php include 'header.php';
if(!isset($_SESSION['user_id'])){
  echo '<p class="card">Please <a href="login.php">login</a> to view your orders.</p>';
  include 'footer.php'; exit;
}
$stmt=$pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders=$stmt->fetchAll();
?>
<h2>My Orders</h2>
<?php if(!$orders): ?>
  <p class="card">You have no orders yet. <a href="index.php">Order now</a></p>
<?php else: foreach($orders as $o): ?>
  <div class="card">
    <strong>Order #<?php echo $o['id']; ?></strong><br>
    Total: ₱<?php echo number_format($o['total'],2); ?><br>
    Method: <?php echo htmlspecialchars($o['payment_method']); ?><br>
    Status: <b><?php echo htmlspecialchars($o['status']); ?></b><br>
    Date: <?php echo $o['created_at']; ?><br>
    <ul>
    <?php
      $stmt2=$pdo->prepare("SELECT oi.qty,oi.price,p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
      $stmt2->execute([$o['id']]);
      foreach($stmt2->fetchAll() as $it){
        echo "<li>{$it['qty']}x ".htmlspecialchars($it['name'])." ₱".number_format($it['price'],2)."</li>";
      }
    ?>
    </ul>
  </div>
<?php endforeach; endif; ?>
<p><a href="index.php" class="btn">Back to Menu</a></p>
<?php include 'footer.php'; ?>
