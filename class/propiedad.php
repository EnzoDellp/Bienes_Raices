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
        //sanetizar los datos

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
        return $resultado;
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

        //asignar el atributo de imagen el nombre de la imagen
        if ($imagen){
            $this->imagen=$imagen;
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
      if (!$this->imagen){
         self::$errores[]="la imagen es obligatoria";
      };
 
   
      return self::$errores;
     
}

//lista todas las propiedades

public static function all(){
    $query="SELECT * FROM propiedades";

  $resultado= self::consultarSQL($query);

    return $resultado;
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
}

