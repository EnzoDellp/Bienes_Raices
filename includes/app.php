<?php 

require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

// Conectarnos a la base de datos
$db = conectarDB();

use App\Active_Record;

Active_record::setDB($db);
