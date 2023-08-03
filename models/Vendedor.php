<?php

namespace Model;

class Vendedor extends ActiveRecord {
    protected static $tabla = 'vendedores';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono'];

    public $id;
    public $nombre;
    public $apellido;    
    public $telefono;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
    }
    public function validar() {

        if(!$this->nombre) { // ! es un no
            self::$errores[] = "El nombre es Obligatorio";
        } 
        if(!$this->apellido) { // ! es un no
            self::$errores[] = "El apellido es Obligatorio";
        } 
        if(!$this->telefono) { // ! es un no
            self::$errores[] = "El telefono es Obligatorio";
        }

    // PREG_MATCH: es una expresion regular que busca un patron dentro de un texto.
    // tiene que ser del 0-9 (asi se sabe que es solo numeros) y que sean 10.
        if(!preg_match('/[0-9]{10}/', $this->telefono)){ 
            self::$errores[] = "Formato no valido";
        }
        return self::$errores;
    }
}