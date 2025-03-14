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

// get_figuras.php - Obtener datos de figuras
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require 'db_connection.php';

    $stmt = $pdo->prepare("SELECT 
    `COL 1` AS `iCveIE`,
    `COL 2` AS `cDesIE`,
    `COL 3` AS `iCveCZ`,
    `COL 4` AS `cDesCZ`,
    `COL 5` AS `iCveMR`,
    `COL 6` AS `cDesMRegion`,
    `COL 7` AS `iCveUO`,
    `COL 8` AS `cDesUO`,
    `COL 9` AS `iCveCE`,
    `COL 10` AS `fRegistroCE`,
    `COL 11` AS `iCveSituacionCE`,
    `COL 12` AS `cDesSituacionCE`,
    `COL 13` AS `iNumEduCE`,
    `COL 14` AS `idFigOp`,
    `COL 15` AS `cRFC`,
    `COL 16` AS `cCURP`,
    `COL 17` AS `cPaterno`,
    `COL 18` AS `cMaterno`,
    `COL 19` AS `cNombre`,
    `COL 20` AS `fRegistro`,
    `COL 21` AS `iCveSubProyecto`,
    `COL 22` AS `cIdenSubPro`,
    `COL 23` AS `iCveDepend`,
    `COL 24` AS `cIdenDepen`,
    `COL 25` AS `iCveVincula`,
    `COL 26` AS `cDesVincula`,
    `COL 27` AS `iCveSituacion`,
    `COL 28` AS `cDesSituacion`,
    `COL 29` AS `fSituacion`,
    `COL 30` AS `iCveMotivoSit`,
    `COL 31` AS `cDesMSituacion`,
    `COL 32` AS `iCveRolFO`,
    `COL 33` AS `cDesRolFO`,
    `COL 34` AS `fRol`,
    `COL 35` AS `iCveAntEscolares`,
    `COL 36` AS `cDesAntEscolares`,
    `COL 37` AS `iTipoVial`,
    `COL 38` AS `cDesVialidad`,
    `COL 39` AS `cDomicilio`,
    `COL 40` AS `cNumExt`,
    `COL 41` AS `iTipoAseHum`,
    `COL 42` AS `cDesAsentamiento`,
    `COL 43` AS `cColonia`,
    `COL 44` AS `iCodPostal`,
    `COL 45` AS `cEMail`,
    `COL 46` AS `cTelefono`,
    `COL 47` AS `iCveMunicipio`,
    `COL 48` AS `cDesMunicipio`,
    `COL 49` AS `iCveLocalidad`,
    `COL 50` AS `cDesLocalidad`,
    `COL 51` AS `cSexo`,
    `COL 52` AS `fActualizaVista`,
    `COL 53` AS `fNacimiento`,
    `COL 54` AS `iNumHijos`
FROM `figurasoperativast`");
    $stmt->execute();
    $figuras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($figuras);
    exit;
}
?>