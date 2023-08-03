<?php

namespace Model;

class Propiedad  extends ActiveRecord { //Gracias a la herencia tenemos todo el codigo disponible

    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedores_id'];

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
        $this->vendedores_id = $args['vendedores_id'] ?? '';
    }

    public function validar() {
        if(!$this->titulo) { // ! es un no
            self::$errores[] = "Debes anadir un titulo";
        }
    
        if(!$this->precio) { // ! es un no
            self::$errores[] = "El precio obligatorio";
        }
    
        if( strlen( $this->descripcion ) < 50 ) { // ! es un no
            self::$errores[] = "La descripcion es obligatoria y debe tener al menos 50 caracteres";
        }
    
        if(!$this->habitaciones) { // ! es un no
            self::$errores[] = "Debes anadir un numero de habitaciones";
        }
    
        if(!$this->wc) { // ! es un no
            self::$errores[] = "Debes anadir un numero de Banos";
        }
    
        if(!$this->estacionamiento) { // ! es un no
            self::$errores[] = "Debes anadir un numero de estacionamiento";
        }
    
        if(!$this->vendedores_id) { // ! es un no
            self::$errores[] = "Elije un vendedor";
        }
    
        if(!$this->imagen) {
             self::$errores[] = 'La Imagen de la propiedad es  Obligatoria';
         }
        
        return self::$errores;
    }
}

