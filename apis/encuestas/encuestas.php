<?php
// Permitir solicitudes desde cualquier origen (*), puedes cambiarlo a tu dominio específico
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight request (cuando se hace un request OPTIONS antes de un POST o GET)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


// login.php - Autenticación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db_connection.php';
    $input = json_decode(file_get_contents("php://input"), true);
    
    $email = $input['email'];
    $password = $input['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $token = bin2hex(random_bytes(32));
        echo json_encode(["success" => true, "token" => $token]);
    } else {
        echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
    }
    exit;
}

// get_encuestas.php - Obtener datos de encuestas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require 'db_connection.php';

    $stmt = $pdo->prepare("SELECT id, nombre_alfabetizador, nombre_educando, calificacion_sesion, avance_educando, dificultad_sesion, dificultad_tipo, dificultad_educando, fecha_registro FROM encuestas");
    $stmt->execute();
    $encuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($encuestas);
    exit;
}
?>