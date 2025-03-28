<?php
$host = 'localhost'; // Cambia esto según tu configuración
$dbname = 'sasabi';
$username = 'consulta_user';
$password = 'password123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
