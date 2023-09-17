<?php
require_once __DIR__."/bluetech/Controllers/RutasController.php";
require_once __DIR__."/istore/Controllers/RutasController.php";

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

if(count(array_filter($arrayRutas)) == 2){
     if(array_filter($arrayRutas)[1] == "bluetech"){
            $router = new controllers\RutasBluetechController();
            $router->index();
     }
     if(array_filter($arrayRutas)[1] == "istore"){
            $router = new controllers\RutasiStoreController();
            $router->index();
     }
}

if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){
        header('Content-Type: application/json; charset=utf-8');
        $response = [
            "status" => 404,
            "msg" => "No Found...",
        ];

        echo json_encode($response);
 }