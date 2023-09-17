<?php
date_default_timezone_set('Canada/Central');

require_once __DIR__."/bluetech/Controllers/RutasController.php";

$router = new controllers\RutasBluetechController();
$router->index();


