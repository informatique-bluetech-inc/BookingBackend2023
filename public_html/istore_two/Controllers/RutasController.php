<?php

namespace Controllers;

class RutasiStoreTwoController
{

    public function index(): void
    {
        echo "SERGIO Estoy index de RutasiStoreTwoController";
        include __DIR__."/../Rutas/Rutas.php";
    }

}
