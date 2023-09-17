<?php

namespace Controllers;

class RutasiStoreController
{

    public function index(): void
    {
        include __DIR__."/../Rutas/Rutas.php";
    }

}
