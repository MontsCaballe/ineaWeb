<?php
require_once './controllers/tableroController.php';
require_once './controllers/tableroController.php';

$config = require 'config.php';
$db = new DatabaseController($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['endpoint'] === 'chart-data') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("");
    }
    if ($_GET['endpoint'] === 'getPlanteles') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("SELECT * FROM   [SASABI].[dbo].[Sedes_NAY]");
    }
    if ($_GET['endpoint'] === 'getMetasMensuales') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("SELECT * FROM [SASABI].[dbo].[LogrosUO_NAY]");
    }
    if ($_GET['endpoint'] === 'getCertificadosEmitidos') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("  SELECT * FROM [SASABI].[dbo].[Certificados_NAY]");
    }
    if ($_GET['endpoint'] === 'getSubproyecto') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("SELECT * FROM [SASABI].[dbo].[Subproyectos_NAY]");
    }
    if ($_GET['endpoint'] === 'getAvanceEducando') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("");
    }
    if ($_GET['endpoint'] === 'getFigurasOperativas') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("SELECT * FROM [SASABI].[dbo].[FigurasOperativas_NAY]");
    }
    if ($_GET['endpoint'] === 'getRezago') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("");
    }
    if ($_GET['endpoint'] === 'getLogros') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getQueryData("SELECT * FROM [SASABI].[dbo].[LogrosUO_NAY]");
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
if ($_POST['endpoint'] === 'login') {
    # code...
}
}