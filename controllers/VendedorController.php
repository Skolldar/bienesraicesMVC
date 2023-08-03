<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;


class VendedorController {
    public static function crear(Router $router) {

        //Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();

        $vendedor = new Vendedor;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Crear una nueva instancia
        $vendedor = new Vendedor($_POST['vendedor']);

        //Validar que no haya campos vacios
        $errores = $vendedor->validar();

        // No hay errores 
        if(empty($errores)){
        $vendedor->guardar();
        }
    
}
    $router->render('vendedores/crear', [
        'errores' => $errores,
        'vendedor' => $vendedor
    ]);

    }


    public static function actualizar(Router $router) {

        //Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();

        $id = validarORedireccionar('/admin'); 

        // Obtener el arrglo del vendedor desde la DB
        $vendedor = Vendedor::find($id);

         
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los valores;
            $args = $_POST['vendedor'];
            
            // //SINCRONIZAR los datos a memoria 
            $vendedor->sincronizar($args);
            
        
            // // Validacion
            $errores = $vendedor->validar();
        
            if(empty($errores)) {
                $vendedor->guardar();
            }
        
        }


            $router->render('vendedores/actualizar', [
                'errores' => $errores,
                'vendedor' => $vendedor
            ]);


    }


    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validaar ID
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
        
            if($id) {
                $tipo = $_POST['tipo'];
        
                if(validarTipoContenido($tipo)) {
                    // Compara lo que vamos a eliminar 
                    if($tipo === 'vendedor') {
                        $vendedor = Vendedor::find($id);
        
                        $vendedor->eliminar();
        
                    } 
                }
            }
    
        }
    }
}