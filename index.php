<?php
include 'header.php';
require_once 'db_connect.php';

// fetch categories
$categories = $pdo->query("SELECT DISTINCT category FROM products")->fetchAll(PDO::FETCH_COLUMN);

foreach($categories as $cat):
    echo "<h2 class='menu-category'>" . htmlspecialchars($cat) . "</h2>";
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->execute([$cat]);
    $products = $stmt->fetchAll();
    
    echo "<div class='menu-grid'>";
    foreach($products as $p): ?>
        <div class="menu-item">
            <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
            <h3><?php echo htmlspecialchars($p['name']); ?></h3>
            <p>₱<?php echo number_format($p['price'], 2); ?></p>
            <form method="post" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                <input type="number" name="qty" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach;
    echo "</div>";
endforeach;
?>
