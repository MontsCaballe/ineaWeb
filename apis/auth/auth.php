<?php
require_once 'vendor/autoload.php';
require 'db_connection.php';

use Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $username = $input['username'];
    $password = $input['password'];

    // Verificar las credenciales
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, hash('sha256', $password)]);

    if ($user = $stmt->fetch()) {
        $key = "your_secret_key";
        $payload = [
            "iss" => "http://yourdomain.com",
            "aud" => "http://yourdomain.com",
            "iat" => time(),
            "exp" => time() + 3600,
            "user" => $username
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        echo json_encode(["token" => $jwt]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid credentials"]);
    }
}
?>