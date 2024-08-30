<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/controlador/conexion.php';
include_once 'archivo.php';

$obj = new Conectar();
$obj2 = new Archivo();

function contienePalabra($cadena, $palabra)
{
    return stripos($cadena, $palabra) !== false;
}
/* $directorio = $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/datos"; */

if (isset($_FILES['archivoTxt1']['name'])) {
    $_SESSION['nombreDeTabla'] = "REGISTROS_RCE";
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['data'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
 /*    $archivo = $directorio . "/" . ['archivoTxt1']['name']; */
    header('Location: ../index.php?respuesta=1');
}
if (isset($_FILES['archivoTxt2']['name'])) {
    $_SESSION['nombreDeTabla'] = "PROPUESTA";
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['data'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
 /*    $archivo = $directorio . "/" . ['archivoTxt1']['name']; */
    header('Location: ../index.php?respuesta=1');
}
if (isset($_FILES["fileToUpload"]["name"])) {
    $cadena = $_FILES["fileToUpload"]["name"] . "";
    $respuesta = 0;
    //Nombres de las tablas
    $palabras = ["COMPRAS", "DEPOSITOS"];
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
        $_SESSION['rpta'] = 2;
        header('Location: ../index.php?respuesta=1');
    }
}
if (isset($_FILES["fileCustom"]["name"])) {
    //Nombre de la tabla
    $textoIngresado = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace('_', '', $_REQUEST['nombreTabla']));
    $_SESSION['nombreDeTabla'] = $textoIngresado;
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['data'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
    header('Location: ../index.php?respuesta=1');
}
if (isset($_FILES["zipToUpload"]["name"])) {
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['zip'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
    header('Location: ../index.php?respuesta=3');
}

?>