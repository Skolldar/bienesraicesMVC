<?php
use Model\ActiveRecord; // ANTES: App/Propiedad; ha sido cambiado a ACTIVERECORD

require __DIR__ . '/../vendor/autoload.php'; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';
require 'config/database.php';


// Conectarnos a la base de datos
$db = conectarDB();

ActiveRecord::setDB($db); // ANTES: App/Propiedad; ha sido cambiado a ACTIVERECORD 
// LUEGO DE ESOS CAMBIOS HARA SIEMPRE REFERENCIA A LA CLASE PADRE Y NO ES NECESARIO VOLVERLO A LLAMAR   

