<?php

namespace APP;

class Propiedad{

    //bade de datos
    protected static $db;

    protected static $columnasDB=["id","titulo","precio","imagen","descripcion","habitaciones","wc","estacionamiento","creado","vendedores_id"];

    //errores
    protected static $errores=[];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;


    //definir la conexion a la BD
    public static function setDB($database){
        self :: $db = $database;
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? 1;
    }

    public function guardar(){
        
        if(!is_null($this->id)){
            //actualizar
            $this->actualizar();
        }else{
            //crear uno nuevo
            $this->crear();
        }
    }

    public function crear(){
     
        $atributos=$this->sanitizarAtributos();
     //*insertar en la base de datos
     //?forma vista en el curso
    //  $query="INSERT INTO propiedades(";
    //  $query.=join(', ',array_keys($atributos));
    //  $query.=") VALUES ('";
    //  $query.=join("','",array_values($atributos));
    //  $query.="')";

     //?forma entendible
    $columnas = join(', ',array_keys($atributos));
    $filas = join("', '",array_values($atributos));
    $query = "INSERT INTO propiedades($columnas) VALUES ('$filas')";
    
    //   debugear($query);
     $resultado=self::$db->query($query);
            //mensaje de exito o error
    if ($resultado) {
        //reedirecion al usuario
        header("location:/admin/index.php?resultado3=1");
          }
    }

    public function actualizar(){
         //sanetizar los datos

         $atributos=$this->sanitizarAtributos();
         $valores=[];
         foreach($atributos as $key =>$value){
            $valores[]= "{$key}='{$value}'";
         }
         $query="UPDATE propiedades SET ";
         $query.= join(', ',$valores); 
         $query.="WHERE id ='".self::$db->escape_string($this->id) . "'";
         $query.="LIMIT 1";
         $resultado= self::$db->query($query);
         if ($resultado) {
            //reedirecion al usuario
            header("location:/admin/index.php?resultado3=2");
              }
    }
    //eliminar registro
    public function eliminar(){
        $query = "DELETE FROM propiedades WHERE id =".self::$db->escape_string($this->id)." LIMIT 1";
        $resultado=self::$db->query($query);
        

        if($resultado) {
            $this->borrarImagen();
            header('location: /admin/index.php');
        }
    }   
    //identificar y unir los atributos de la BD
    public function atributos(){
        $atributos=[];
        foreach(self::$columnasDB as $columna){
            if ($columna ==="id") continue;
            $atributos[$columna]=$this->$columna;
        }
            return $atributos;
       
    }

    public function sanitizarAtributos(){
        $atributos=$this->atributos();
        $sanetizado=[];


        foreach($atributos as $key => $value){
            $sanetizado[$key]= self::$db -> escape_string($value);
        }
        return$sanetizado;

    }

    //subida de archivos
    public function setImagen($imagen){
        //Elimina la imagen anterior
        if(!is_null($this->id)){
           $this->borrarImagen();
        }
        //asignar el atributo de imagen el nombre de la imagen
        if ($imagen){
            $this->imagen=$imagen;
        }
    }
    //Eliminar el archivo
    public function borrarImagen(){
      //Elimina la imagen anterior
      if(!is_null($this->id)){
        //comprobar si existe la imagen
        $existeArchivo=file_exists(CARPETA_INAGENES.$this->imagen);
        if ($existeArchivo){
            unlink(CARPETA_INAGENES.$this->imagen);

        }
    }  
    }
//validacion

public static function getErrores(){
    return self::$errores;
}


public function validar(){
    
    if (!$this->titulo) {
        self::$errores[]="Debes añadir un titulo";
     }
    
     if (!$this->precio) {
         self::$errores[]="Debes añadir un precio";
      }
      if ( strlen($this->descripcion)<50) {
         self::$errores[]="La descripcion es obligatioria y debe tener al menos 50 caracteres";
      }
      if (!$this->habitaciones) {
         self::$errores[]="Debes Añadir un numero de habitaciones";
      }
      if (!$this->wc) {
         self::$errores[]="Debes Añadir un numero de baños";
      }
      if (!$this->estacionamiento) {
         self::$errores[]="Debes Añadir un numero de estaciomanientos";
      }
      if (!$this->vendedores_id) {
         self::$errores[]="Debes elegir un vendedor";
      }
    //   if (!$this->imagen){
    //      self::$errores[]="la imagen es obligatoria";
    //   };
 
   
      return self::$errores;
     
}

//lista todos los registros

public static function all(){
    $query="SELECT * FROM propiedades";

  $resultado= self::consultarSQL($query);

    return $resultado;
}
//busca una propiedad por su id
public static function find($id){
    $query="SELECT * FROM propiedades WHERE id=${id}";
    
    //consulta para obtener los vendedores
    $resultado =self::consultarSQL($query);

    return (array_shift($resultado));

}

public static function consultarSQL($query){

    //consultar bd
    $resultado=self::$db->query($query);
    
    //iterar los resultados
    $array=[];
    while ($registro=$resultado->fetch_assoc()){
        $array[]=self::crearObjeto($registro);
    }
    
    //liberar la memoria
    $resultado->free();
    //retornar los resultados
    return $array;
}
protected static function crearObjeto($registro){

    $objeto=new self;

    foreach($registro as $key => $value){

        if(property_exists($objeto,$key)){

            $objeto->$key=$value;
        }
    }
    return $objeto;

}

//sincroniza el objeto en memoria con los cambios realizaods por el usuario
    public function sincronizar($args = [] ){
        foreach($args as $key => $value){
            if(property_exists($this,$key) && !is_null($value)){
                $this->$key=$value;
            }
        }
    }
}

