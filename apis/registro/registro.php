<?php
require 'db_connection.php';
global $pdo;
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(["message" => "Método no permitido"]);
    exit();
}

// Obtener los datos de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

$nombre = $input['nombre'];
$genero = $input['genero'];
$situacion_migratoria = $input['situacion_migratoria'];
$edad = $input['edad'];
$lugar_origen = $input['lugar_origen'];
$nivel_educativo = $input['nivel_educativo'];
$discapacidad = $input['discapacidad'];
$correo = $input['correo'];
$telefono = $input['telefono'];
$comentarios = $input['comentarios'];

// -------------------------- Función auxiliar --------------------------

$input = json_decode(file_get_contents('php://input'), true);

// if (!isset($input['username']) || !isset($input['email']) || !isset($input['contrasena'])) {
//     http_response_code(400);
//     echo json_encode(["message" => "Campos obligatorios: username, email, contrasena"]);
//     return;
// }


$stmt = $pdo->prepare("INSERT INTO `prospectos`( `nombre`, `genero`, `situacion_migratoria`, `edad`, `lugar_origen`, `nivel_educativo`, `discapacidad`, `correo`, `telefono`, `comentarios`, `fecha_registro`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->execute([$nombre, $genero, $situacion_migratoria,$edad, $lugar_origen, $nivel_educativo,$discapacidad,$correo,$telefono,$comentarios, date()]);
echo json_encode(["message" => "Usuario creado exitosamente"]);
?>