<?php
require 'db_connection.php';

// Determinar el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetRequest();
        break;
    case 'POST':
        handlePostRequest();
        break;
    case 'PUT':
        handlePutRequest();
        break;
    case 'DELETE':
        handleDeleteRequest();
        break;
    default:
        http_response_code(405); // Método no permitido
        echo json_encode(["message" => "Método no permitido"]);
        break;
}

// -------------------------- Métodos del CRUD --------------------------

function handleGetRequest() {
    global $pdo;

    if (isset($_GET['id_usuario'])) {
        $id_usuario = (int)$_GET['id_usuario'];
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Usuario no encontrado"]);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM usuarios");
        echo json_encode($stmt->fetchAll());
    }
}

function handlePostRequest() {
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['username']) || !isset($input['email']) || !isset($input['contrasena'])) {
        http_response_code(400);
        echo json_encode(["message" => "Campos obligatorios: username, email, contrasena"]);
        return;
    }

    $username = $input['username'];
    $email = $input['email'];
    $password = password_hash($input['contrasena'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, contrasena) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    echo json_encode(["message" => "Usuario creado exitosamente"]);
}

function handlePutRequest() {
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($_GET['id_usuario'])) {
        http_response_code(400);
        echo json_encode(["message" => "Se requiere el ID del usuario"]);
        return;
    }

    $id_usuario = (int)$_GET['id_usuario'];

    $updates = [];
    if (isset($input['username'])) $updates['username'] = $input['username'];
    if (isset($input['email'])) $updates['email'] = $input['email'];
    if (isset($input['contrasena'])) $updates['contrasena'] = password_hash($input['contrasena'], PASSWORD_DEFAULT);

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(["message" => "No se enviaron campos para actualizar"]);
        return;
    }

    $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($updates)));
    $stmt = $pdo->prepare("UPDATE usuarios SET $setClause WHERE id_usuario = ?");
    $stmt->execute([...array_values($updates), $id_usuario]);

    echo json_encode(["message" => "Usuario actualizado exitosamente"]);
}

function handleDeleteRequest() {
    global $pdo;

    if (!isset($_GET['id_usuario'])) {
        http_response_code(400);
        echo json_encode(["message" => "Se requiere el ID del usuario"]);
        return;
    }

    $id_usuario = (int)$_GET['id_usuario'];

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);

    echo json_encode(["message" => "Usuario eliminado exitosamente"]);
}
?>