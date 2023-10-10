<?php

namespace Controllers;

class RutasBluetechController
{

    public function index(): void
    {
        include __DIR__."/../Rutas/Rutas.php";
    }

}

?>