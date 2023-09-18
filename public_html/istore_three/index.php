<?php
date_default_timezone_set('Canada/Central');

require_once __DIR__."/Controllers/RutasController.php";

$router = new controllers\RutasiStoreThreeController();
$router->index();


