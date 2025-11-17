<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>3M's Cafe</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">
  <header class="site-header">
    <div class="container">
      <h1>
        <img src="images/logo.jpg" alt="3M's Cafe" class="logo">
        <a href="index.php">3M's Cafe</a>
      </h1>
      <nav>
        <a href="index.php">Menu</a>
        <?php if(isset($_SESSION['user_id'])): ?>
          <a href="cart.php">Cart</a>
          <a href="my_orders.php">My Orders</a>
          <a href="logout.php">Logout</a>
          <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="admin/dashboard.php">Dashboard</a>
          <?php endif; ?>
        <?php else: ?>
          <a href="register.php">Register</a>
          <a href="login.php">Login</a>
          <a href="admin/login_admin.php">Admin Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container">
