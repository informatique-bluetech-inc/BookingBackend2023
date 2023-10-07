<?php

namespace Controllers;

class RutasInfotechcorpController
{

    public function index(): void
    {
        include __DIR__."/../Rutas/Rutas.php";
    }

}
