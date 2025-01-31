<?php
require 'db_connection.php';

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
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}

function handleGetRequest() {
    global $pdo;

    if (isset($_GET['id_rol'])) {
        $id_rol = (int)$_GET['id_rol'];
        $stmt = $pdo->prepare("SELECT * FROM roles WHERE id_rol = ?");
        $stmt->execute([$id_rol]);
        echo json_encode($stmt->fetch() ?: ["message" => "Rol no encontrado"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM roles");
        echo json_encode($stmt->fetchAll());
    }
}

function handlePostRequest() {
    global $pdo;
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['nombre_rol']) || empty($input['nombre_rol'])) {
        http_response_code(400);
        echo json_encode(["message" => "El nombre del rol es obligatorio"]);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO roles (nombre_rol, descripcion) VALUES (?, ?)");
    $stmt->execute([$input['nombre_rol'], $input['descripcion'] ?? null]);
    echo json_encode(["message" => "Rol creado exitosamente"]);
}

function handlePutRequest() {
    global $pdo;
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($_GET['id_rol'])) {
        http_response_code(400);
        echo json_encode(["message" => "Se requiere el ID del rol"]);
        return;
    }

    $id_rol = (int)$_GET['id_rol'];
    $updates = [];
    if (isset($input['nombre_rol'])) $updates['nombre_rol'] = $input['nombre_rol'];
    if (isset($input['descripcion'])) $updates['descripcion'] = $input['descripcion'];

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(["message" => "No se enviaron campos para actualizar"]);
        return;
    }

    $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($updates)));
    $stmt = $pdo->prepare("UPDATE roles SET $setClause WHERE id_rol = ?");
    $stmt->execute([...array_values($updates), $id_rol]);

    echo json_encode(["message" => "Rol actualizado exitosamente"]);
}

function handleDeleteRequest() {
    global $pdo;

    if (!isset($_GET['id_rol'])) {
        http_response_code(400);
        echo json_encode(["message" => "Se requiere el ID del rol"]);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM roles WHERE id_rol = ?");
    $stmt->execute([(int)$_GET['id_rol']]);

    echo json_encode(["message" => "Rol eliminado exitosamente"]);
}
?>