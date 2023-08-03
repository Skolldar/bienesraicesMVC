<?php

namespace Model;

class ActiveRecord {
    //Base de DATOS
    protected static $db; //no se puede acceder y solo sera uno nada mas
    //identificar que forman van a tener los datos.
    protected static $columnasDB = [];
    protected static $tabla = '';
 
    //ERRORES 
    protected static $errores = [];

    //Definir la conexion a la DB // SETDB es la clase PADRE
    public static function setDB($database) {
        self::$db = $database; //self hace referencia a los atributos static, usa $ 
    } // NO ES NECESARIO CAMBIAR SELF A STATIC, porque siempre, ya sean propiedades o sean vendedores, siempre se conectaran a la misma base de datos


    public function guardar() {
        if(!is_null($this->id)) {
            // Actualizar
            $this->actualizar();
        } else {
            //Creando un nuevo registro
            $this->crear();
        }
    }
    public function crear() { // public va a hacer referencia a todo lo THIS 
        
        //Sanitizar los datos
    
        $atributos = $this->sanitizarAtributos();

        //Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

       $resultado = self::$db->query($query);

        //MEnsaje de Exito
        if($resultado) {
        // Redireccionar al usuario
          header('Location: /admin?resultado=1'); //solo se coloca donde no hay HTML
        }
    }
    
    public function actualizar() {
        //Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";
      
        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario
      
            header('Location: /admin?resultado=2'); //solo se coloca donde no hay HTML
          }
    }

    //Eliminar un Registro
    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1"; //lo escapamos para no tener un problema en caso que alguien quiera inyectar codigo
        $resultado = self::$db->query($query);
        if($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }   


// Identificar y unir los Atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) { // se cambia porque no tiene nada que ve en la db
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
     

        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
    //Subida de archivos
        public function setImagen($imagen) {

    // Elimina la imagen previa
            if(!is_null($this->id)) {
                $this->borrarImagen();
               
            }
    //Assignar al atributo de imagen el nombre del imagen 
        if($imagen) {
            $this->imagen = $imagen;
        }
 }
 // Elimina el archivo
    public function borrarImagen() {
         // Comprobar si existe el archivo
         $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);

         if($existeArchivo) {
             unlink(CARPETA_IMAGENES . $this->imagen);
         }
      
    }

    // Validacion 
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores; //self antes que hacia referencias al active record... Ahora es static para que vaya al atributo de la clase hijo
    }

    //Lista todas las registro
    public static function all() {
       $query = "SELECT * FROM " . static::$tabla;

       $resultado = self::consultarSQL($query);
       return $resultado;
    }

    //Obtiene determinado numero de registros.
    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
         $resultado = self::consultarSQL($query);
        return $resultado;
     }

    // Busca ub registro por su ID
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        // Consultar la basse de datos
        $resultado = self::$db->query($query);
        //Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        //Liberar la memoria
        $resultado->free();

        //Retornar
        return $array;
    }


    //Crear objeto en memoria que se itere en la base de datos
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }


    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar( $args = [] ) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}