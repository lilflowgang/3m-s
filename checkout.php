<?php include 'header.php';
if(!isset($_SESSION['user_id'])){
  echo '<p class="card">Please <a href="login.php">login</a> to checkout.</p>';
  include 'footer.php'; exit;
}
$cart=$_SESSION['cart']??[];
if(!$cart){ echo '<p class="card">Cart empty. <a href="index.php">Back</a></p>'; include 'footer.php'; exit; }

$in = implode(',', array_fill(0,count($cart),'?'));
$stmt=$pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
$stmt->execute(array_keys($cart));
$rows=$stmt->fetchAll(); $total=0;
foreach($rows as $r) $total+=$r['price']*$cart[$r['id']];

if($_SERVER['REQUEST_METHOD']==='POST'){
  $method=$_POST['payment_method'];
  $details=$_POST['payment_details']??'';
  $pdo->beginTransaction();
  try{
    $pdo->prepare("INSERT INTO orders (user_id,total,payment_method,payment_details) VALUES (?,?,?,?)")
        ->execute([$_SESSION['user_id'],$total,$method,$details]);
    $oid=$pdo->lastInsertId();
    $stmt=$pdo->prepare("INSERT INTO order_items (order_id,product_id,qty,price) VALUES (?,?,?,?)");
    foreach($rows as $r){
      $stmt->execute([$oid,$r['id'],$cart[$r['id']],$r['price']]);
    }
    $pdo->commit(); unset($_SESSION['cart']);
    echo "<p class='card'>✅ Order #$oid placed!</p>";
    if($method==='Gcash'){
      echo "<div class='card'><h3>GCash Instructions</h3><p>Pay ₱".number_format($total,2)." to 0966-710-1410</p><img src='images/gcash_qr.jpg' width='200'></div>";
    } elseif($method==='Maya'){
      echo "<div class='card'><h3>Maya Instructions</h3><p>Pay ₱".number_format($total,2)." to 0994-558-2134</p><img src='images/maya_qr.jpg' width='200'></div>";
    } elseif($method==='Bank'){
      echo "<div class='card'><h3>Bank Transfer</h3><p>BDO - 3M's Cafe - 1234-5678-90</p></div>";
    } else {
      echo "<p class='card'>Pay cash upon pickup/delivery.</p>";
    }
    echo '<p><a href="index.php" class="btn">Back to Menu</a></p>';
    include 'footer.php'; exit;
  }catch(Exception $e){
    $pdo->rollBack();
    echo '<p class="card">Error: '.htmlspecialchars($e->getMessage()).'</p>';
  }
}
?>
<h2>Checkout</h2>
<p class="card">Total: ₱<?php echo number_format($total,2); ?></p>
<form method="post">
  <label>Payment Method</label> <br><br>
  <select name="payment_method" class="form-control">
    <option>Gcash</option>
    <option>Maya</option>
    <option>Bank</option>
    <option>Cash</option>
  </select> <br><br>
  <div class="form-group">
    <input class="form-control" name="payment_details" placeholder="Payment reference (optional)">
  </div>
  <button class="btn" type="submit">Place Order</button>
</form>
<p><a href="cart.php">Back to Cart</a></p>
<?php include 'footer.php'; ?>
