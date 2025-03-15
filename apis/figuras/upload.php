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

// 📂 Ruta del archivo CSV
$uploadFile = __DIR__ . "/temp/figurasOperativasAALFANAYF.csv";

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
$delimiter = ",";

foreach ($delimiters as $d) {
    if (substr_count($firstLine, $d) > 0) {
        $delimiter = $d;
        break;
    }
}

// 🔹 Saltar la primera línea si contiene encabezados
$firstRow = true;
$linea = 1;
$successCount = 0;
$errorCount = 0;
$errores = [];

while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
    if ($firstRow) {
        $firstRow = false;
        continue;
    }

    // 🔹 Verificar número de columnas
    if (count($data) !== 54) {
        $errores[] = "⚠️ Línea $linea: Se esperaban 54 valores, pero se encontraron " . count($data);
        error_log("⚠️ Línea $linea: Se esperaban 54 valores, pero se encontraron " . count($data));
        $errorCount++;
        continue;
    }

    // 🔹 Escapar valores y manejar NULL correctamente
    foreach ($data as $key => $value) {
        $data[$key] = empty(trim($value)) ? "NULL" : "'" . $conn->real_escape_string($value) . "'";
    }

    // 🔹 Construir la consulta SQL manualmente
    $sql = "INSERT INTO figurasALFANAY VALUES (" . implode(", ", $data) . ")";

    // 🔹 Log de depuración para ver la consulta SQL antes de ejecutarla
    error_log("SQL a ejecutar en línea $linea: " . $sql);

    // 🔹 Ejecutar la consulta
    if (!$conn->query($sql)) {
        $errores[] = "⚠️ Linea $sql: " . $conn->error;
        error_log("⚠️ Error en la línea $linea: " . $conn->error);
        $errorCount++;
    } else {
        $successCount++;
    }

    $linea++;
}

// 🔹 Cerrar recursos
fclose($handle);
$conn->close();

// ✅ Responder con éxito
echo json_encode([
    "message" => "✅ Importación completada.",
    "insertados" => $successCount,
    "errores" => $errorCount,
    "detalleErrores" => $errores
]);
?>
