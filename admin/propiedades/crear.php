<?php 
require "../../includes/app.php";


use APP\Propiedad;

use Intervention\Image\ImageManagerStatic as Image;
estaAutenticado();

//base de datos
$db=conectarDB();


//consulta para obtener vendeores

$consulta="SELECT * FROM vendedores";
$res=mysqli_query($db,$consulta);

// arreglo con mensajes de errores
$errores=Propiedad::getErrores();



$titulo='';
$precio ='';
$descripcion='';
$habitaciones='';
$wc='';
$estacionamiento='';
$vendedores_id='';


//ejecutar el codigo despues de que el usuario envia el formulario

if($_SERVER["REQUEST_METHOD"]==="POST"){
    //crea una nueva instancia
    $propiedad=new Propiedad($_POST);



//generar un nombre unico para cada imagen
$nombreImagen=md5(uniqid(rand(),true)).".jpg";
//crear carpeta

if($_FILES['imagen']['tmp_name']){
    $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800,600);    
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
       $resultado=$propiedad->guardar();

        //mensaje de exito o error
    if ($resultado) {
      //reedirecion al usuario
      header("location:/admin/index.php?resultado3=1");
        }
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
    <fieldset>
        <legend>Información General</legend>

        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo"name="titulo" placeholder="titulo propiedad" value="<?php echo $titulo;?>" >

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" placeholder="precio propiedad"value="<?php echo $precio;?>" >

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

        <label for="descripcion">Descrípcion</label>
        <textarea  id="descripcion" name="descripcion" ><?php echo $descripcion;?> </textarea>
    </fieldset>


    <fieldset>
        <legend>Información de la propiedad</legend>

        <label for="habitaciones">habitaciones:</label>
        <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones;?>" >

        <label for="wc">baños:</label>
        <input type="number" id="wc" name="wc" placeholder="Ej: 1" min="1" max="9" value=<?php echo $wc;?> >

        <label for="estacionamiento">estacionamiento:</label>
        <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 2" min="1" max="9" value="<?php echo $estacionamiento;?>" >

    </fieldset>

    <fieldset>
                <legend>Vendedor</legend>
            <select name="vendedores_id">
            <option value="">--Selecione--</option>
            <?php while($vendedor=mysqli_fetch_assoc($res)) { ?>
                <option  <?php echo $vendedores_id===$vendedor['id'] ? 'selected':''; ?> value="<?php echo $vendedor['id'];?>"><?php echo $vendedor['nombre']." ".$vendedor['apellido'];  ?></option>
            <?php }; ?>

            </select>
            </fieldset>  
                    
        <input type="submit" value="Crear Propiedad"  class="boton boton-verde" >
        </form>


    </main>

    <?php 
inculirtemplate("footer");
?>