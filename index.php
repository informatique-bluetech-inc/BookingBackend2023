<?php
require_once __DIR__."/common/RutasController.php";

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

$BLUETECH = "BlueTech";
$ISTORE1 = "iStore1";
$ISTORE2 = "iStore2";
$ISTORE3 = "iStore3";
$INFOTECHCORP = "InfoTechCorp";

if(count(array_filter($arrayRutas)) == 2){

    if(array_filter($arrayRutas)[1] == "bluetech"){
        $router = new RutasController();
        $router->index($BLUETECH);
    }
    
    if(array_filter($arrayRutas)[1] == "istore"){
        $router = new RutasController();
        $router->index($ISTORE1);
    }
    
    if(array_filter($arrayRutas)[1] == "istore_two"){
        $router = new RutasController();
        $router->index($ISTORE2);
    }

    if(array_filter($arrayRutas)[1] == "istore_three"){
        $router = new RutasController();
        $router->index($ISTORE3);
    }
    
    if(array_filter($arrayRutas)[1] == "infotechcorp"){
        $router = new RutasController();
        $router->index($INFOTECHCORP);
    }

}

?>