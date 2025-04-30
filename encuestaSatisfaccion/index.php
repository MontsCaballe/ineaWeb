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

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INEA Nayarit</title>


  <!-- CSS -->
  <link href="./favicons/favicon.ico" rel="shortcut icon">
  <link href="https://framework-gb.cdn.gob.mx/assets/styles/main.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Respond.js soporte de media queries para Internet Explorer 8 -->
  <!-- ie8.js EventTarget para cada nodo en Internet Explorer 8 -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/ie8/0.2.2/ie8.js"></script>
    <![endif]-->

  <style>
    /* Formulario centrado */
    .form-container {
      width: 50%;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.7);
      /* Fondo blanco semitransparente */
      box-sizing: border-box;
      border-radius: 8px;
      text-align: center;
      /* Centra el texto dentro del formulario */
    }

    @media (max-width: 768px) {
      .form-container {
        width: 80%;
        /* En pantallas más pequeñas, el formulario ocupa el 80% */
      }
    }

    @media (max-width: 576px) {
      .form-container {
        width: 90%;
        /* En teléfonos, el formulario ocupa el 90% */
      }
    }

    /* Asegúrate de que el contenedor tenga la imagen de fondo */
    .image-container {
      background-image: url('https://inea.nayarit.gob.mx/INEA_PLECA-REDES-2.jpg');
      /* Cambia esto a la ruta de tu imagen */
      background-size: cover;
      /* Hace que la imagen cubra toda el área */
      background-position: center;
      /* Centra la imagen en la pantalla */
      background-repeat: no-repeat;
      /* Evita que la imagen se repita */
      height: 100vh;
      /* Asegura que ocupe toda la altura de la ventana del navegador */
      width: 100%;
      /* Asegura que cubra todo el ancho */
      display: flex;
      /* Habilita el uso de flexbox */
      justify-content: center;
      /* Centra el formulario horizontalmente */
      align-items: center;
      /* Centra el formulario verticalmente */
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input,
    select,
    button {
      margin: 10px 0;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }

    .container-fluid {
      padding: 0;
    }

    @media (max-width: 768px) {
      .form-container {
        width: 80%;
        margin-top: 20%;
      }
    }

    @media (max-width: 576px) {
      .form-container {
        width: 95%;
        margin-top: 20%;
      }
    }
  </style>
</head>

<body>

  <!-- Contenido -->
  <main class="page">
    <div class="container-fluid">
      <nav class="navbar navbar-inverse sub-navbar navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#subenlaces">
              <span class="sr-only">Interruptor de Navegación</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><img src="../images/logo.png" alt="Logo"></a>
          </div>
          <div class="collapse navbar-collapse" id="subenlaces">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">Inicio</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Quiénes
                  somos <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Nuestro Instituto</a></li>
                  <li><a href="#mision">Misión</a></li>
                  <li><a href="#vision">Visión</a></li>

                  <li class="divider"></li>
                  <li><a href="#">Directorio</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Oferta
                  Educativa <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Alfabetización</a></li>
                  <li><a href="#">MEV Aprende INEA</a></li>
                  <li><a href="#">Requisitos de Ingreso</a></li>
                  <li><a href="#">MIB</a></li>

                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Servicios
                  en Línea <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <!-- <li><a href="https://inea.nayarit.gob.mx/tablero-digital/">Tablero Digital</a></li> -->
                  <li><a href="https://inea.nayarit.gob.mx/tablero-digital/">Tablero Digital Tiempo Real</a>
                  </li>
                  <li><a href="login.html">Logros por Asesor</a></li>
                  <li><a href="login.html">Logros por Técnico Docente</a></li>
                  <li><a href="login.html">Logros por Educando</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Registrate en línea</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                  aria-expanded="false">Plataformas Institucionales <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">SIGA</a></li>
                  <li><a href="#">SAEL</a></li>
                  <li><a href="#">SINAPLAC</a></li>
                  <li><a href="#">SATIC</a></li>
                  <!-- <li class="divider"></li>
                  <li><a href="#">Enlace separado</a></li> -->
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                  aria-expanded="false">Transparencia <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="transparencia.html">Portal de Transparencia</a></li>
                  <li><a href="armo.html">SEVAC</a></li>
                  <li><a href="evaluacion.html">Evaluación</a></li>

                  <!-- <li class="divider"></li>
                  <li><a href="#">Enlace separado</a></li> -->
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-10 col-12 mx-auto">
        <div class="image-container">
        <div class="form-container">
            <!-- <center> -->

            <form action="" method="POST">
              <h2>Encuesta de Satisfacción</h2>
              <label for="nombreAlfabetizador" class="required">Nombre Completo Alfabetizador:</label>
              <input type="text" id="nombreAlfabetizador" name="nombreAlfabetizador" required>

              <label for="nombreEducando" class="required">Nombre Completo Educando:</label>
              <input type="text" id="nombreEducando" name="nombreEducando" required>

              <label for="calificacion_sesion" class="required">Calificación de la Sesión:</label>
              <select id="calificacion_sesion" name="calificacion_sesion" required>
                <option value="Buena">Buena</option>
                <option value="Regular">Regular</option>
                <option value="Mala">Mala</option>
              </select>

              <label for="avance_educando" class="required">Calificación Avance Educando:</label>
              <select id="avance_educando" name="avance_educando" required>
                <option value="Buena">Buena</option>
                <option value="Regular">Regular</option>
                <option value="Mala">Mala</option>
              </select>

              <label for="dificultad_sesion" class="required">Dificultad en la Sesión:</label>
              <select id="dificultad_sesion" name="dificultad_sesion" required>
                <option value="Si">Si</option>
                <option value="No">No</option>
              </select>

              <div id="tipo_dificultad_container" style="display:none;">
                <label for="dificultad_tipo" class="required">Tipo de Dificultad:</label>
                <select id="dificultad_tipo" name="dificultad_tipo" required>
                  <option value="Alta">Alta</option>
                  <option value="Intermedia">Intermedia</option>
                  <option value="Baja">Baja</option>
                </select>
              </div>

              <label for="dificultad_educando" class="required">Dificultad del Educando:</label>
              <select id="dificultad_educando" name="dificultad_educando" required>
                <option value="Alta">Alta</option>
                <option value="Regular">Regular</option>
                <option value="Baja">Baja</option>
              </select>

              <button type="submit">Registrar</button>
            </form>
            <!-- </center> -->
          </div>
        </div>

         

        </div>
      </div>

    </div>
  </main>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
  <script src="scripts.js"></script>

</body>

</html>