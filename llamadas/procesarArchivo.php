<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/controlador/conexion.php';
include_once 'archivo.php';

$obj = new Conectar();
$obj2 = new Archivo();

function contienePalabra($cadena, $palabra) {
    return stripos($cadena, $palabra) !== false;
}

if (isset($_FILES["fileToUpload"]["name"])) {
    
    $cadena = $_FILES["fileToUpload"]["name"]."";
    $respuesta = 0;
    //Nombres de las tablas
    $palabras = ["COMPRAS","DEPOSITOS","SUNAT"];
    foreach ($palabras as $key => $palabra) {
        if (contienePalabra($cadena, $palabra)) {
            $_SESSION['nombreDeTabla'] = $palabra;
            $archivo = "" . $obj2->subirArchivo();
            $_SESSION['data'] = $archivo;
            $_SESSION['rpta'] = $obj2->getUploadOK();
            header('Location: ../index.php?respuesta=1');
            $respuesta++;
            break;
        }
    }
    if ($respuesta == 0) {
        unset($_SESSION['nombreDeTabla']);
        header('Location: ../index.php?blank');
    }
}
if (isset($_FILES["zipToUpload"]["name"])) {
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['zip'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
    header('Location: ../index.php?respuesta=3');
}

?>