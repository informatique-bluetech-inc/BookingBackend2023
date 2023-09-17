<?php
date_default_timezone_set('Canada/Central');

require_once __DIR__."/istore/Controllers/RutasController.php";

$router = new controllers\RutasiStoreController();
$router->index();


