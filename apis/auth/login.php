<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(["message" => "Método no permitido"]);
    exit();
}

// Obtener los datos de la solicitud
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

// Validar los datos de entrada
if (empty($username) || empty($password)) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(["message" => "El nombre de usuario y la contraseña son obligatorios"]);
    exit();
}

// Consultar el usuario en la base de datos
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['contrasena'])) {
    // Si las credenciales son correctas, obtener los roles del usuario
    $roles = getRoles($user['id_usuario'], $pdo);

    // Registrar la acción en la auditoría (login exitoso)
    registrarAuditoria($pdo, $user['id_usuario'], 1, 'Login exitoso'); // Suponiendo que el sistema tiene id 1

    // Devolver la respuesta con la información del usuario
    echo json_encode([
        "message" => "Login exitoso",
        "user" => [
            "id_usuario" => $user['id_usuario'],
            "username" => $user['username'],
            "email" => $user['email'],
            "roles" => $roles
        ]
    ]);
} else {
    // Credenciales inválidas
    http_response_code(401); // No autorizado
    echo json_encode(["message" => "Credenciales inválidas"]);

    // Registrar el intento de login fallido en la auditoría
    if ($user) {
        registrarAuditoria($pdo, $user['id_usuario'], 1, 'Intento de login fallido');  // Suponiendo que el sistema tiene id 1
    }
}

// -------------------------- Función auxiliar --------------------------

function getRoles($userId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT r.nombre_rol 
        FROM roles r 
        JOIN usuarios_roles ur ON r.id_rol = ur.id_rol 
        WHERE ur.id_usuario = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Función para registrar la acción de auditoría
function registrarAuditoria($pdo, $id_usuario, $id_sistema, $accion) {
    $sql = "INSERT INTO auditoria_accesos (id_usuario, id_sistema, accion) 
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $id_sistema, $accion]);
}
?>
