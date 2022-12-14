<?php 

    require '../includes/app.php';
    estaAutenticado();

//importar clases
use App\Propiedad;
use App\Vendedor;

//metodo para obtener todas las propiedades

$propiedades=Propiedad::all();
$vendedores=Vendedor::all();


    // Muestra mensaje condicional
    $resultado = $_GET['resultado'] ?? null;


    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

       
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id) {
            $tipo=$_POST["tipo"];
            if(validarTipoContenido($tipo)){


                //compra lo que vamos a eliminar

                if($tipo==="vendedor"){
                    $vendedor=Vendedor::find($id);
                    $vendedor->eliminar();
                }else if($tipo==="propiedad") {
                $propiedad=Propiedad::find($id);
                $propiedad->eliminar();
                }
            }

           
      
            // Eliminar el archivo
          
    

          
        }

        
    }

    // Incluye un template

   inculirtemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
    
        <?php 
        $mensaje=mostrarNotificacion( intval($resultado));
        if($mensaje){?>

            <p class="alerta exito"><?php echo s($mensaje);?> </p>

        <?php }?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <a href="/admin/vendedores/crear.php" class="boton boton-amarillo">Nuevo Vendedor</a>

        <h2>Propiedades</h2>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody> <!-- Mostrar los Resultados -->
                <?php foreach($propiedades as $propiedad):  ?>
                <tr>
                    <td><?php echo $propiedad->id; ?></td>
                    <td><?php echo $propiedad ->titulo; ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad ->imagen; ?>" class="imagen-tabla"> </td>
                    <td>$ <?php echo $propiedad ->precio; ?></td>
                    <td>
                        <form method="POST" class="w-100">

                            <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">
                            <input type="hidden" name="tipo" value="propiedad">

                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        
                        <a href="propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Vendedores</h2>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tel??fono</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody> <!-- Mostrar los Resultados -->
                <?php foreach( $vendedores as $vendedor ): ?>
                <tr>
                    <td><?php echo $vendedor->id; ?></td>
                    <td><?php echo $vendedor->nombre . " " . $vendedor->apellido; ?></td>
                    <td>$ <?php echo $vendedor->telefono; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $vendedor->id; ?>">
                            <input type="hidden" name="tipo" value="vendedor">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        
                        <a href="vendedores/actualizar.php?id=<?php echo $vendedor->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

<?php 


    inculirtemplate('footer');
?>