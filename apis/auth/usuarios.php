<?php
require 'db_connection.php';

$stmt = $pdo->query("SELECT * FROM usuarios");
echo json_encode($stmt->fetchAll());
?>