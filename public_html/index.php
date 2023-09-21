<?php
date_default_timezone_set('Canada/Central');
require_once __DIR__."/bluetech/Controllers/RutasController.php";
require_once __DIR__."/istore/Controllers/RutasController.php";
require_once __DIR__."/istore_two/Controllers/RutasController.php";
require_once __DIR__."/istore_three/Controllers/RutasController.php";
require_once __DIR__."/infotechcorp/Controllers/RutasController.php";

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
     if(array_filter($arrayRutas)[1] == "istore_two"){
      echo "SERGIO Estoy rutas";
       $router = new controllers\RutasiStoreTwoController();
       $router->index();
     }
     if(array_filter($arrayRutas)[1] == "istore_three"){
       $router = new controllers\RutasiStoreThreeController();
       $router->index();
     }
     if(array_filter($arrayRutas)[1] == "infotechcorp"){
       $router = new controllers\RutasInfotechcorpController();
       $router->index();
     }
}

