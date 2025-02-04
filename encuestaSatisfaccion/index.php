<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "consulta_user"; // Cambia esto según tu configuración
$password = "password123"; // Cambia esto según tu configuración
$dbname = "educandos";

// Definir cabeceras para permitir solicitudes desde otros dominios (CORS)
header("Access-Control-Allow-Origin: *");

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
} else {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombreAlfabetizador = $_POST['nombreAlfabetizador'];
    $nombreEducando = $_POST['nombreEducando'];
    $calificacionSesion = $_POST['calificacion_sesion'];
    $avanceEducando = $_POST['avance_educando'];
    $dificultadSesion = $_POST['dificultad_sesion'];
    $dificultadTipo = $_POST['dificultad_tipo'] ?? null; // Solo se usa si dificultad_sesion es 'Si'
    $dificultadEducando = $_POST['dificultad_educando'];

    // Validar si el correo ya existe en la base de datos
    // Aquí puede agregar una validación adicional si es necesario

    // Preparar la consulta SQL para insertar los datos en la tabla "encuestas"
    $sql = "INSERT INTO encuestas (nombre_alfabetizador, nombre_educando, calificacion_sesion, avance_educando, dificultad_sesion, dificultad_tipo, dificultad_educando)
            VALUES ('$nombreAlfabetizador', '$nombreEducando', '$calificacionSesion', '$avanceEducando', '$dificultadSesion', '$dificultadTipo', '$dificultadEducando')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
      // Redirigir a la página de éxito
      header("Location: index.php");
      exit(); // Importante para evitar que el script continúe después de redirigir
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return;
    }
  }

  $conn->close();
}
?>