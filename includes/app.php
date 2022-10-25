<?php

require "funciones.php";
//conexion a db
require "config/database.php";
//composer
require __DIR__."/../vendor/autoload.php";

//conencatar a la bd
$db=conectarDB();

use APP\Propiedad;

Propiedad::setDB($db);

