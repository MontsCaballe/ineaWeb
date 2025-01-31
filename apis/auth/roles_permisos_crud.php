<?php
require 'db_connection.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        handlePostRequest();
        break;
    case 'DELETE':
        handleDeleteRequest();
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}

function handlePostRequest() {
    global $pdo;
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id_rol']) || !isset($input['id_permiso'])) {
        http_response_code(400);
        echo json_encode(["message" => "Campos obligatorios: id_rol, id_permiso"]);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO roles_permisos (id_rol, id_permiso) VALUES (?, ?)");
    $stmt->execute([$input['id_rol'], $input['id_permiso']]);

    echo json_encode(["message" => "Permiso asignado al rol exitosamente"]);
}

function handleDeleteRequest() {
    global $pdo;

    if (!isset($_GET['id_rol']) || !isset($_GET['id_permiso'])) {
        http_response_code(400);
        echo json_encode(["message" => "Se requieren los parámetros id_rol y id_permiso"]);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM roles_permisos WHERE id_rol = ? AND id_permiso = ?");
    $stmt->execute([(int)$_GET['id_rol'], (int)$_GET['id_permiso']]);

    echo json_encode(["message" => "Permiso eliminado del rol exitosamente"]);
}
?>