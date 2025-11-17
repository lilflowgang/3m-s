<?php include 'header.php';
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])){
    $pid = (int)$_POST['product_id'];
    $qty = max(1,(int)($_POST['qty'] ?? 1));
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
}

if(isset($_GET['remove'])){ unset($_SESSION['cart'][(int)$_GET['remove']]); header('Location: cart.php'); exit; }
if(isset($_GET['clear'])){ unset($_SESSION['cart']); header('Location: cart.php'); exit; }

$cart = $_SESSION['cart'];
$items=[]; $total=0;
if($cart){
  $in = implode(',', array_fill(0,count($cart),'?'));
  $stmt=$pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
  $stmt->execute(array_keys($cart));
  foreach($stmt->fetchAll() as $r){
    $qty=$cart[$r['id']];
    $sub=$qty*$r['price'];
    $items[]=['product'=>$r,'qty'=>$qty,'sub'=>$sub];
    $total+=$sub;
  }
}
?>
<h2>Your Cart</h2>
<?php if(!$items): ?>
  <p class="card">Empty cart. <a href="index.php">Back</a></p>
<?php else: foreach($items as $it): ?>
  <div class="card">
    <strong><?php echo $it['product']['name']; ?></strong> x <?php echo $it['qty']; ?> — ₱<?php echo $it['sub']; ?>
    <a href="cart.php?remove=<?php echo $it['product']['id']; ?>">Remove</a>
  </div>
<?php endforeach; ?>
  <h3>Total: ₱<?php echo $total; ?></h3>
  <a class="btn" href="checkout.php">Checkout</a>
  <a class="btn" href="index.php" style="background:#555;">Continue</a>
  <a class="btn" href="cart.php?clear=1" style="background:#a00;" onclick="return confirm('Clear cart?');">Clear</a>
<?php endif; ?>
<?php include 'footer.php'; ?>
