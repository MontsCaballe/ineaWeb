<?php
require_once './controllers/tableroController.php';
require_once './controllers/tableroController.php';

$config = require 'config.php';
$db = new DatabaseController($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['endpoint'] === 'chart-data') {
        require 'controllers/ChartController.php';
        $chartController = new TableroController($db);
        $chartController->getChartData();
    }
}
