<?php 

require "../../includes/funciones.php";
$auth=estaAutenticado();

   if(!$auth){
       header("location:/index.php");
   }

$id=$_GET["id"];
$id=filter_var($id,FILTER_VALIDATE_INT); //validar id valido

if (!$id) {
   header("location:/admin/index.php");
}

//base de datos

require '../../includes/config/database.php';

$db=conectarDB();


//obtener los datos de la propiedad

$consulta1="SELECT * FROM propiedades WHERE id=${id}";
$resultado1=mysqli_query($db,$consulta1);
$propiedad1=mysqli_fetch_assoc($resultado1);
// var_dump($propiedad1);

// echo" <pre>";
// var_dump($propiedad1);
// echo"</pre>";

//consulta para obtener vendeores

$consulta="SELECT * FROM vendedores";
$res=mysqli_query($db,$consulta);

// arreglo con mensajes de errores
$errores=[];


$titulo=$propiedad1["titulo"];
$precio =$propiedad1["precio"];
$descripcion=$propiedad1["descripcion"];
$habitaciones=$propiedad1["habitaciones"];
$wc=$propiedad1["wc"];
$estacionamiento=$propiedad1["estacionamiento"];
$vendedores_id=$propiedad1["vendedores_id"];
$imagenPropiedad=$propiedad1["imagen"];


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
    

     //validar por tamaño(1mb max)
     $medida=1000 * 1000;
     if ($imagen["size"]>$medida){
        $errores[]="La imagen es muy pesada";
     };
    

     //revisar que el arreglo de errores este vacio

     if (empty($errores)) {
          //crear carpeta donde se guardaran las img
          $carpetaImagenes="'../../../../imagenes/";


          //para que no cree siempre la carpeta
  
          if(!is_dir($carpetaImagenes)){
              mkdir($carpetaImagenes);
  
          }
          $nombreImagen="";
        //subida de archivos
       
       if ($imagen["name"]){
         //eleminar imagen previa

         unlink($carpetaImagenes.$propiedad1["imagen"]);

         //generar un nombre unico para cada imagen
         $nombreImagen=md5(uniqid(rand(),true)).".jpg";
        

         // subir imagen
  
          move_uploaded_file($imagen["tmp_name"],$carpetaImagenes . "/". $nombreImagen);

       
       }else{
        $nombreImagen=$propiedad1["imagen"];
       }

      
       
        

          //*insertar en la base de datos
          $query = " UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = ${wc}, estacionamiento = ${estacionamiento}, vendedores_id = ${vendedores_id} WHERE id = ${id} ";
          
    

    $resultado=mysqli_query($db,$query);

    if ($resultado) {
      //reedirecion al usuario
      header("location:/admin/index.php?resultado3=2");
        }
    }



}


inculirtemplate("header");

?>


    <main class="contenedor seccion">


        <h1>Actualizar</h1>
        <a href="/admin/index.php" class="boton boton-verde" >Volver</a>

        <?php foreach($errores as $error):?>
            <div class="alerta error">
            <?php echo$error;?>
            </div>
        <?php endforeach;?>

    <!--post para informacion segura como passwords/ get para pasar info a otra pagina -->
        <form class="formulario" method="POST"  enctype="multipart/form-data">
    <fieldset>
        <legend>Información General</legend>

        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo"name="titulo" placeholder="titulo propiedad" value="<?php echo $titulo;?>" >

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" placeholder="precio propiedad"value="<?php echo $precio;?>" >

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
            <img src="/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small" >
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
                    
        <input type="submit" value="Actualizarl Propiedad"  class="boton boton-verde" >
        </form>


    </main>

    <?php 
inculirtemplate("footer");
?>