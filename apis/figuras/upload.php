<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 📌 Configuración de la base de datos
$host = 'localhost';
$dbname = 'sasabi';
$username = 'consulta_user';
$password = 'password123';

// 🔹 Conectar a MySQL
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["message" => "❌ Error de conexión a la base de datos: " . $conn->connect_error]));
}

// ❌ Verificar si se subió un archivo
if (!isset($_FILES["csvFile"])) {
    echo json_encode(["message" => "❌ No se ha subido ningún archivo."]);
    exit;
}

// ❌ Verificar si se subió un archivo
if (!isset($_FILES["csvFile"])) {
    echo json_encode(["message" => "No se ha subido ningún archivo."]);
    exit;
}

// 📂 Obtener la ruta del directorio actual (donde está `upload.php`)
$uploadDir = __DIR__ . "/temp/";

// 🔹 Crear la carpeta `temp/` si no existe
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true)) {
        echo json_encode(["message" => "❌ No se pudo crear la carpeta $uploadDir. Verifica permisos."]);
        exit;
    }
}

// 🔹 Asegurar que la carpeta `temp/` tenga permisos de escritura
if (!is_writable($uploadDir)) {
    chmod($uploadDir, 0775);
}

// 📂 Definir la ruta del archivo dentro de `temp/`
$uploadFile = $uploadDir . basename($_FILES["csvFile"]["name"]);

// 🔹 Mover el archivo subido a `temp/`
if (!move_uploaded_file($_FILES["csvFile"]["tmp_name"], $uploadFile)) {
    echo json_encode(["message" => "❌ Error al mover el archivo a $uploadDir. Verifica permisos o espacio en disco."]);
    exit;
}

// ✅ Responder con éxito
echo json_encode(["message" => "✅ Archivo subido correctamente a $uploadFile."]);
// 📂 Abrir el archivo CSV desde la nueva ubicación
$handle = fopen($uploadFile, "r");

if (!$handle) {
    echo json_encode(["message" => "❌ Error al abrir el archivo en $uploadFile."]);
    exit;
}

// 🔹 Saltar la primera línea si contiene encabezados
$firstRow = true;

// 📌 Preparar la consulta de inserción
$sql = "INSERT INTO figurasALFANAY (
    iCveIE, cDesIE, iCveCZ, cDesCZ, iCveMR, cDesMRegion, iCveUO, cDesUO, iCveCE, 
    fRegistroCE, iCveSituacionCE, cDesSituacionCE, iNumEduCE, idFigOp, cRFC, cCURP, 
    cPaterno, cMaterno, cNombre, fRegistro, iCveSubProyecto, cIdenSubPro, iCveDepend, 
    cIdenDepen, iCveVincula, cDesVincula, iCveSituacion, cDesSituacion, fSituacion, 
    iCveMotivoSit, cDesMSituacion, iCveRolFO, cDesRolFO, fRol, iCveAntEscolares, 
    cDesAntEscolares, iTipoVial, cDesVialidad, cDomicilio, cNumExt, iTipoAseHum, 
    cDesAsentamiento, cColonia, iCodPostal, cEMail, cTelefono, iCveMunicipio, 
    cDesMunicipio, iCveLocalidad, cDesLocalidad, cSexo, fActualizaVista, fNacimiento, 
    iNumHijos
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// 📌 Preparar la consulta SQL
$stmt = $conn->prepare($sql);

// ❌ Verificar si la consulta se preparó correctamente
if (!$stmt) {
    echo json_encode(["message" => "❌ Error en la consulta SQL: " . $conn->error]);
    exit;
}

// 📂 Leer cada fila del CSV e insertar en la base de datos
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if ($firstRow) { // Saltar encabezados
        $firstRow = false;
        continue;
    }

    // 🔹 Asignar valores desde CSV
    $stmt->bind_param(
        "issississssssssssssssssssssssssssssssssssssssssssssssss", 
        $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], 
        $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14], 
        $data[15], $data[16], $data[17], $data[18], $data[19], $data[20], $data[21], 
        $data[22], $data[23], $data[24], $data[25], $data[26], $data[27], $data[28], 
        $data[29], $data[30], $data[31], $data[32], $data[33], $data[34], $data[35], 
        $data[36], $data[37], $data[38], $data[39], $data[40], $data[41], $data[42], 
        $data[43], $data[44], $data[45], $data[46], $data[47], $data[48], $data[49], 
        $data[50], $data[51], $data[52], $data[53]
    );

    $stmt->execute();
}

// 🔹 Cerrar recursos
fclose($handle);
$stmt->close();
$conn->close();

// ✅ Responder con éxito
echo json_encode(["message" => "✅ Archivo CSV importado correctamente desde $uploadFile."]);
?>
