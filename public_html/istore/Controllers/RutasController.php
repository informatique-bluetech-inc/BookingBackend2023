<?php

namespace Controllers;

class RutasBluetechController
{

    public function index(): void
    {
        echo "entre";die;
        include __DIR__."/../Rutas/Rutas.php";
    }

}

?>