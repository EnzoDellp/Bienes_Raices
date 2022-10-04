<?php

function conectarDB():mysqli{
    $db=mysqli_connect("localhost","root","adrianferrari15","bienesraices_crud");
    if (!$db) {
        echo "Error no se pudo conectar";
       exit; 
    }
    return $db;
    echo"Se pudo conectar la la bd";
}
