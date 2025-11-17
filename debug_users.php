<?php
require_once 'db_connect.php';

echo "<h2>Users Table Debug</h2>";

$stmt = $pdo->query("SELECT id, name, email, is_admin, password FROM users");
$users = $stmt->fetchAll();

if(!$users){
    echo "<p>No users found in database.</p>";
} else {
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>is_admin</th><th>Password Hash</th></tr>";
    foreach($users as $u){
        echo "<tr>";
        echo "<td>".$u['id']."</td>";
        echo "<td>".htmlspecialchars($u['name'])."</td>";
        echo "<td>".htmlspecialchars($u['email'])."</td>";
        echo "<td>".$u['is_admin']."</td>";
        echo "<td><code>".htmlspecialchars($u['password'])."</code></td>";
        echo "</tr>";
    }
    echo "</table>";
}
