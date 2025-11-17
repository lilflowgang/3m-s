<?php
require_once 'db_connect.php';

$name = "Admin";
$email = "Florence@3ms.com";
$passwordPlain = "12345678";
$passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);

// delete old admin if exists
$pdo->prepare("DELETE FROM users WHERE email=?")->execute([$email]);

// insert new admin
$pdo->prepare("INSERT INTO users (name,email,password,is_admin) VALUES (?,?,?,1)")
    ->execute([$name, $email, $passwordHash]);

echo "✅ New admin created: $email / $passwordPlain";
