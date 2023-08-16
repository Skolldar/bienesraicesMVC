<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index(Router $router) {
        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render('paginas/nosotros',[

        ]);
    }

    public static function propiedades(Router $router) {
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {
        $id = validarOredireccionar('/propiedades');

        //busca la propiedad por su id 
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog(Router $router) {
        $router->render('paginas/blog', [

        ]);
    }

    public static function entrada(Router $router) {
        $router->render('paginas/entrada', [

        ]);
    }

    public static function contacto(Router $router) {

        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuestas = $_POST['contacto'];

            // Crear una instancia de PHPMailer
            $mail = new PHPMailer();

            //Configurar SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
            $mail->MAIL_ENCRYPTION= 'tls'; //transport layer security
            $mail->Port = $_ENV['EMAIL_PORT'];


            //Configurar el contenido del mail
            $mail->setFrom('admin@bienesraices.com'); //QUIEN LO ENVIA
            $mail->addAddress('admin@bienesraices.com', 'Bienes Raices'); //A CUAL MAIL VA A LLEGAR
            $mail->Subject = 'Tienes un Nuevo Mensaje'; // LO primero que los usuario veran


            //Habilitar HTML 
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';


            // Definir Contenido
            $contenido = '<html>'; // .= toma en cuenta el codigo anterior
            $contenido .= '<p>Tienes un nuevo mensaje</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . '</p>';

            //Enviar de forma condicional algunos campos de email o telefono
            if($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligio ser contactado por Telefono:</p>';
                $contenido .= '<p>Telefono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p>Fecha Contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p>Hora Contacto: ' . $respuestas['hora'] . '</p>';

            } else {
                $contenido .= '<p>Eligio ser contactado por Email:</p>';
                $contenido .= '<p>Email: ' . $respuestas['email'] . '</p>';
            }

            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p>Precio o Presupuesto: $' . $respuestas['precio'] . '</p>';
            $contenido .= '<p>Prefiere ser Contactado por: ' . $respuestas['contacto'] . '</p>';
           
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = ' Esto es texto alternativo';

            //Enviar el email
            if($mail->send()) {
                $mensaje = "Mensaje enviado Correctamente";
            } else {
                $mensaje = "El mensaje no se pudo enviar";
            }
        }
        
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje

        ]);
    }
}