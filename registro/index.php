<?php
// Conexi�n a la base de datos
$servername = "localhost";
$username = "consulta_user"; // Cambia esto seg�n tu configuraci�n
$password = "password123"; // Cambia esto seg�n tu configuraci�n
$dbname = "prospectos_db";


// Definir cabeceras para permitir solicitudes desde otros dominios (CORS)
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");


$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
  // echo 'conexion fallida';
  die("Conexión fallida: " . $conn->connect_error);
}else{
  // echo 'conexion exitosa';
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo 'en el post';
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $situacion_migratoria = $_POST['situacion_migratoria'];
    $edad = $_POST['edad'];
    $lugar_origen = $_POST['lugar_origen'];
    $nivel_educativo = $_POST['nivel_educativo'];
    $discapacidad = $_POST['discapacidad'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $comentarios = $_POST['comentarios'];
  
    // Preparar y ejecutar la consulta para insertar los datos
    $sql = "INSERT INTO prospectos (nombre, genero, situacion_migratoria, edad, lugar_origen, nivel_educativo, discapacidad, correo, telefono, comentarios)
              VALUES ('$nombre', '$genero', '$situacion_migratoria', '$edad', '$lugar_origen', '$nivel_educativo', '$discapacidad', '$correo', '$telefono', '$comentarios')";
    // echo 'en el sql   ' . $sql;
  
    $res = $conn->query($sql);
    // var_dump($res);
    if ($conn->query($sql) === TRUE) {
      // echo "Registro exitoso";
      // header("Location: " . $_SERVER['PHP_SELF']);
      return;
    } else {
      // echo "Error: " . $sql . "<br>" . $conn->error;
      // header("Location: " . $_SERVER['PHP_SELF']);
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
    .form-container {
      width: 45%;
      padding: 20px;
      background-color: #f4f4f4;
      box-sizing: border-box;
    }

    .image-container {
      width: 100%;
      background-image: url('INEA_PLECA-REDES-2.jpg');
      /* Coloca la URL de tu imagen */
      background-size: cover;
      background-position: left;
      height: 100vh;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input,
    select {
      margin: 10px 0;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }

    h2 {
      margin-bottom: 20px;
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
            <a class="navbar-brand" href="/"><img src="./images/logo.png" alt="Logo"></a>
          </div>
          <div class="collapse navbar-collapse" id="subenlaces">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">Inicio</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Qui�nes
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
                  <li><a href="evaluacion.html">Evaluación Contable</a></li>

                  <!-- <li class="divider"></li>
                  <li><a href="#">Enlace separado</a></li> -->
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="row">

        <div class="col-md-12">
          <!-- <div class="container-fluid"> -->
          <div class="col-md-6">
            <div class="form-container">
              <br>
              <br>
              <br>

              <h2>Regístrate para estudiar con nosotros</h2>
              <form action="" method="POST">
                <label for="nombre" class="required">Nombre(s):</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="genero" class="required">Género:</label>
                <select id="genero" name="genero" required>
                  <option value="Hombre">Hombre</option>
                  <option value="Mujer">Mujer</option>
                </select>

                <label for="situacion_migratoria" class="required">Situación migratoria:</label>
                <select id="situacion_migratoria" name="situacion_migratoria" required>
                  <option value="Mexicano">Mexicano</option>
                  <option value="retornado">Retornado</option>
                  <option value="refugiado">Refugiado</option>
                  <option value="extranjero">Extranjero</option>
                  <option value="viviendo en México">Viviendo en México</option>
                </select>

                <label for="edad" class="required">Edad:</label>
                <input type="number" id="edad" name="edad" required>

                <label for="lugar_origen" class="required">Lugar de origen:</label>
                <input type="text" id="lugar_origen" name="lugar_origen" required>

                <label for="nivel_educativo" class="required">Nivel Educativo de interés:</label>
                <input type="text" id="nivel_educativo" name="nivel_educativo" required>

                <label for="discapacidad">¿Tienes alguna discapacidad? En caso de tenerla, favor de especificarla:</label>
                <textarea id="discapacidad" name="discapacidad"></textarea>

                <label for="correo" class="required">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="telefono" class="required">Teléfono de contacto:</label>
                <input type="tel" id="telefono" name="telefono" required>

                <label for="comentarios">Comentarios:</label>
                <textarea id="comentarios" name="comentarios"></textarea>

                <button type="submit">Registrar</button>
              </form>
            </div>
          </div>
          <!-- <div class="col-md-6"> -->
          <div class="image-container"></div>
          <!-- </div>          -->

          <!-- </div> -->

        </div>


      </div>

    </div>
  </main>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>


</body>

</html>