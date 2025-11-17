<?php
session_start();
require_once '../db_connect.php';
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){ header('Location: login_admin.php'); exit; }
$id=(int)$_GET['id'];
$pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
header("Location: products.php"); exit;
