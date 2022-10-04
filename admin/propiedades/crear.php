<?php 

require "../../includes/funciones.php";
$auth = estaAutenticado();

if(!$auth) {
    header('Location: /index.php');
}
require '../../includes/config/database.php';



//base de datos
$db=conectarDB();


//consulta para obtener vendeores

$consulta="SELECT * FROM vendedores";
$res=mysqli_query($db,$consulta);

// arreglo con mensajes de errores
$errores=[];


$titulo='';
$precio ='';
$descripcion='';
$habitaciones='';
$wc='';
$estacionamiento='';
$vendedores_id='';


// $numero="1HOLA";
// $numero2="hola";

//sanitizar

// $res1=filter_var($numero,FILTER_SANITIZE_NUMBER_INT);
// var_dump($res1);
// exit;

//ejecutar el codigo despues de que el usuario envia el formulario

if($_SERVER["REQUEST_METHOD"]==="POST"){
 

   
    // echo "<pre>";
    // var_dump($_POST);
    // echo"</pre>";
    

    // echo "<pre>";
    // var_dump($_FILES);
    // echo"</pre>";
   

    $titulo= mysqli_real_escape_string($db,$_POST['titulo']);
    $precio = mysqli_real_escape_string($db,$_POST['precio']);
    $descripcion= mysqli_real_escape_string($db,$_POST['descripcion']) ;
    $habitaciones= mysqli_real_escape_string($db,$_POST['habitaciones']) ;
    $wc= mysqli_real_escape_string($db,$_POST['wc']) ;
    $estacionamiento= mysqli_real_escape_string($db,$_POST['estacionamiento']);
    $vendedores_id= mysqli_real_escape_string($db,$_POST['vendedor']);
    $creado=date('Y/m/d');
 
    //asignar flies hacia una variable
    
   $imagen=$_FILES["imagen"];


    if (!$titulo) {
       $errores[]="Debes añadir un titulo";
    }
    // echo "<pre>";
    // var_dump($errores);
    // echo"</pre>";
   
    if (!$precio) {
        $errores[]="Debes añadir un precio";
     }
     if ( strlen($descripcion)<50) {
        $errores[]="La descripcion es obligatioria y debe tener al menos 50 caracteres";
     }
     if (!$habitaciones) {
        $errores[]="Debes Añadir un numero de habitaciones";
     }
     if (!$wc) {
        $errores[]="Debes Añadir un numero de baños";
     }
     if (!$estacionamiento) {
        $errores[]="Debes Añadir un numero de estaciomanientos";
     }
     if (!$vendedores_id) {
        $errores[]="Debes elegir un vendedor";
     }
     if (!$imagen["name"] || $imagen["error"]){
        $errores[]="la imagen es obligatoria";
     };

     //validar por tamaño(1mb max)
     $medida=1000 * 1000;

     if ($imagen["size"]>$medida){
        $errores[]="La imagen es muy pesada";
     };
    

     //revisar que el arreglo de errores este vacio

     if (empty($errores)) {

        //subida de archivos
       

        //crear carpeta
        $carpetaImagenes="'../../../../imagenes";


        //para que no cree siempre la carpeta

        if(!is_dir($carpetaImagenes)){
            mkdir($carpetaImagenes);

        }
        //generar un nombre unico para cada imagen
        $nombreImagen=md5(uniqid(rand(),true)).".jpg";
        

        //subir imagen

        move_uploaded_file($imagen["tmp_name"],$carpetaImagenes . "/". $nombreImagen);
        

          //*insertar en la base de datos
          $query = " INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id ) VALUES ( '$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedores_id' ) ";

    // echo$query;

    $resultado=mysqli_query($db,$query);

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
            <select name="vendedor">
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