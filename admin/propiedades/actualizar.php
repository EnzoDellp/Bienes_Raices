<?php

use App\Propiedad;
use App\Vendedor;

require "../../includes/app.php";

use Intervention\Image\ImageManagerStatic as Image;


estaAutenticado();


$id=$_GET["id"];
$id=filter_var($id,FILTER_VALIDATE_INT); //validar id valido

if (!$id) {
   header("location:/admin/index.php");
}

//obtener los datos de la propiedad
$propiedad=Propiedad::find($id);

//consulta para obtener vendeores
$vendedores=Vendedor::all();

// arreglo con mensajes de errores
$errores=Propiedad::getErrores();


//ejecutar el codigo despues de que el usuario envia el formulario

if($_SERVER["REQUEST_METHOD"] === "POST"){
    //asignar los atributos
    $args=$_POST["propiedad"];
  
    $Propiedad=$propiedad->sincronizar($args);
   
    
    //validacion
    $errores=$propiedad->validar();
     //Subida de archivos
     //genenar nombre unico
     $nombreImagen=md5(uniqid(rand(),true)).".jpg";

     if($_FILES['propiedad']['tmp_name']['imagen']){
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);    
        $propiedad->setImagen($nombreImagen);
        
     }

     //verificar que el arreglo de errores este vacio
     if (empty($errores)) {
        if($_FILES['propiedad']['tmp_name']['imagen']){
        //guardar la imganen
        $image->save(CARPETA_INAGENES.$nombreImagen);
    }
     
        
          //*insertar en la base de datos
        $propiedad->guardar();
          



    if ($resultado) {
      //reedirecion al usuario
      header("location:/admin/index.php?resultado3=2");
        }
    }



}


inculirtemplate("header");

?>


  
<main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin/index.php" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_propiedades.php'; ?>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
        
    </main>

    <?php 
inculirtemplate("footer");
?>