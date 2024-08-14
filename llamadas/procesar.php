<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/controlador/conexion.php';
include_once 'archivo.php';
$obj = new Conectar();
$obj2 = new Archivo();
if (isset($_FILES["fileToUpload"]["name"])) {
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['data'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
    header('Location: ../index.php?respuesta=1');
}
if (isset($_FILES["excelToUpload"]["name"])) {
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['data'] = $archivo;
    $_SESSION['rpta'] = $obj2->getUploadOK();
    header('Location: ../index.php?respuesta=2');
}
if (isset($_FILES["zipToUpload"]["name"])) {
    $archivo = "" . $obj2->subirArchivo();
    $_SESSION['rpta'] = $obj2->getUploadOK();
    header('Location: ../index.php?respuesta=1');
}

?>