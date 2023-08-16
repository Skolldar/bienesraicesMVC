<?php

namespace MVC;

class Router {
    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }


    public function comprobarRutas() {

        session_start();

        $auth = $_SESSION['login'] ?? null;

        // Arreglo de rutas protegidas..
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];
    
        $urlActual = strtok($_SERVER['REQUEST_URI'], '?' ) ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo === 'GET') {
            
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;

        }

        // Proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('Location: /');
        }

            // debuguear($fn);

        if($fn) {
          //la URL existe y hay una funcion asociada  
        call_user_func($fn, $this);
        } else {
        echo "Pagina no encontrada";
        }
    }

    // Muestra una vista 
    public function render($view, $datos = []) { // le da mostrar a una vista
        
        foreach($datos as $key => $value) {
            $$key = $value; // $$ sig: variable de variable, asi se da a entender que no tiene el mismo nombre todas las key
        }

        ob_start(); //inicia el almacenamiento en memoria
        
        include __DIR__ . "/views/$view.php"; // almacena en memoria esta vista en la variable de contenido
        
        $contenido = ob_get_clean(); //limpia la memoria 
    
        include __DIR__ . "/views/layout.php";
    }   
}