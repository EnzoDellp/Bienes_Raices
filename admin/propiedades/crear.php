<?php 
require "../../includes/app.php";


use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;
estaAutenticado();



$propiedad = new Propiedad;

//consulta para obtener vendeores
$vendedores=Vendedor::all();



// arreglo con mensajes de errores
$errores=Propiedad::getErrores();



//ejecutar el codigo despues de que el usuario envia el formulario

if($_SERVER["REQUEST_METHOD"]==="POST"){
    //crea una nueva instancia
    $propiedad=new Propiedad($_POST["propiedad"]);
   



//generar un nombre unico para cada imagen
$nombreImagen=md5(uniqid(rand(),true)).".jpg";
//crear carpeta
//resize a la imagen
if($_FILES['propiedad']['tmp_name']['imagen']){
    $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);    
    $propiedad->setImagen($nombreImagen);
    
 }
 //validar
 $errores= $propiedad->validar();
 $carpetaImagenes="'../../../../imagenes";



 

//setear la imagen

     //realiza un resize con intervation
 
    
   

     if (empty($errores)) {
       
        //asignar flies hacia una variable
       
        //carpeta para subir img
        if(!is_dir(CARPETA_INAGENES)){
            mkdir(CARPETA_INAGENES);
        }
        //*subida de archivos
       
        //guardar imagen en servidor
        $image ->save(CARPETA_INAGENES.$nombreImagen);

        //guardar en la base de datos
       $propiedad->guardar();

   
    }
}


inculirtemplate("header");

?>


    <main class="contenedor seccion">


        <h1>Crear</h1>
        <a href="/admin/index.php" class="boton boton-verde" >Volver</a>

        <?php foreach($errores as $error):?>
            <div class="alerta error">
            <?php echo$error;?>
            </div>
        <?php endforeach;?>

    <!--post para informacion segura como passwords/ get para pasar info a otra pagina -->
        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <?php include "../../includes/templates/formulario_propiedades.php" ?>
                    
        <input type="submit" value="Crear Propiedad"  class="boton boton-verde" >
        </form>


    </main>

    <?php 
inculirtemplate("footer");
?>