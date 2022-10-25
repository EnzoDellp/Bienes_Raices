<?php
define("TEMPLATES_URL",__DIR__."/templates");
define("FUNCIONES_URL",__DIR__."funciones.php");
define('CARPETA_INAGENES',__DIR__.'/../imagenes/');

function inculirtemplate(string $nombre, bool $inicio = false){

    include TEMPLATES_URL."/${nombre}.php";

};

function estaAutenticado(){
    session_start();
    if(!$_SESSION["login"]){
        header("location:/index.php");
    }
}

function debugear($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//escapa/sanetizar el HTML

function s($html):string{
    $s=htmlspecialchars($html);
    return $s;
}