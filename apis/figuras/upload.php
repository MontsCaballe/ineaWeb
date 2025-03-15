<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 📌 Configuración de la Base de Datos
$host = 'localhost';
$dbname = 'sasabi';
$username = 'consulta_user';
$password = 'password123';

// 🔹 Conectar a MySQL
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["message" => "❌ Error de conexión a la base de datos: " . $conn->connect_error]));
}

// 📂 Ruta del archivo CSV (ajustar si es necesario)
$uploadFile = __DIR__ . "/temp/" . "figurasOperativasAALFANAYF.csv"; // Cambia esto por el nombre real

// 🔹 Verificar si el archivo existe
if (!file_exists($uploadFile)) {
    echo json_encode(["message" => "❌ No se encontró el archivo en $uploadFile."]);
    exit;
}

// 📂 Abrir el archivo CSV
$handle = fopen($uploadFile, "r");
if (!$handle) {
    echo json_encode(["message" => "❌ Error al abrir el archivo en $uploadFile."]);
    exit;
}

// 🔹 Detectar el delimitador (`,`, `;`, `\t`)
$firstLine = fgets($handle);
rewind($handle);
$delimiters = [",", ";", "\t"];
$delimiter = ","; // Valor por defecto

foreach ($delimiters as $d) {
    if (substr_count($firstLine, $d) > 0) {
        $delimiter = $d;
        break;
    }
}

// 🔹 Saltar la primera línea si contiene encabezados
$firstRow = true;

// 📌 Arreglo con los nombres de las columnas
$columnNames = [
    "iCveIE", "cDesIE", "iCveCZ", "cDesCZ", "iCveMR", "cDesMRegion", "iCveUO", "cDesUO", "iCveCE",
    "fRegistroCE", "iCveSituacionCE", "cDesSituacionCE", "iNumEduCE", "idFigOp", "cRFC", "cCURP",
    "cPaterno", "cMaterno", "cNombre", "fRegistro", "iCveSubProyecto", "cIdenSubPro", "iCveDepend",
    "cIdenDepen", "iCveVincula", "cDesVincula", "iCveSituacion", "cDesSituacion", "fSituacion",
    "iCveMotivoSit", "cDesMSituacion", "iCveRolFO", "cDesRolFO", "fRol", "iCveAntEscolares",
    "cDesAntEscolares", "iTipoVial", "cDesVialidad", "cDomicilio", "cNumExt", "iTipoAseHum",
    "cDesAsentamiento", "cColonia", "iCodPostal", "cEMail", "cTelefono", "iCveMunicipio",
    "cDesMunicipio", "iCveLocalidad", "cDesLocalidad", "cSexo", "fActualizaVista", "fNacimiento",
    "iNumHijos"
];

// 📌 Crear la consulta SQL dinámicamente
$placeholders = implode(", ", array_fill(0, count($columnNames), "?"));
$sql = "INSERT INTO figurasALFANAY (" . implode(", ", $columnNames) . ") VALUES ($placeholders)";

// 📌 Preparar la consulta SQL
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error en la consulta SQL: " . $conn->error);
    echo json_encode(["message" => "❌ Error en la consulta SQL: " . $conn->error]);
    exit;
}

echo json_encode(["message" => "✅ Consulta preparada correctamente."]);

// 📂 Leer cada fila del CSV e insertar en la base de datos
$linea = 1;
echo json_encode(["message" => "❌ antes del while."]);
while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
    echo json_encode(["message" => "❌ En el while."]);
    if ($firstRow) { // Saltar encabezados
        $firstRow = false;
        continue;
    }

    // Verificar si la cantidad de columnas es correcta
    if (count($data) !== 54) {
        echo json_encode(["message" => "⚠️ Error en la línea $linea: Se esperaban 54 columnas, pero se encontraron " . count($data)]);
        continue;
    }

    // 🔹 Reemplazar valores vacíos con `NULL`
    foreach ($data as $key => $value) {
        $data[$key] = empty(trim($value)) ? NULL : $value;
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

    if (!$stmt->execute()) {
        echo json_encode(["message" => "⚠️ Error en la línea $linea: " . $stmt->error]);
    }

    $linea++;
}

// 🔹 Cerrar recursos
fclose($handle);
$stmt->close();
$conn->close();

// ✅ Responder con éxito
echo json_encode(["message" => "✅ Archivo CSV importado correctamente."]);
?>
