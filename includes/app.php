<?php

require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php'; 


// Conectarnos a la base de datos
$db = conectarDB();

use Model\ActiveRecord; // ANTES: App/Propiedad; ha sido cambiado a ACTIVERECORD
ActiveRecord::setDB($db); // ANTES: App/Propiedad; ha sido cambiado a ACTIVERECORD 
// LUEGO DE ESOS CAMBIOS HARA SIEMPRE REFERENCIA A LA CLASE PADRE Y NO ES NECESARIO VOLVERLO A LLAMAR   

