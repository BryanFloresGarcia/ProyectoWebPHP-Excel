<?php
session_start();
include_once "upload.php";
// Nombre del proceso a verificar
$nombreProceso = "EXCEL.EXE";
// Ejecutar el proceso
shell_exec("start excel " . $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/MACRO_EXCEL_" . $_SESSION['tabla'].".xlsm");
// Esperar a que el proceso termine
$obj = new Upload();
do {
    sleep(1); // Esperar 1 segundo antes de verificar de nuevo
    
} while ($obj->procesoEnEjecucion($nombreProceso));
session_destroy();
// Continuar con el código PHP después de que el proceso haya terminado
if (isset($_POST['download']) && $_POST['download'] === 'true') {
    // Ruta del archivo que deseas que el usuario descargue
    $archivo = $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/MACRO_EXCEL_" . $_SESSION['tabla'].".xlsm";
    // Verificar si el archivo existe
    if (file_exists($archivo)) {
        // Definir las cabeceras para la descarga
        header('Content-Description: File Transfer');
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));
        // Limpiar el buffer de salida
        flush();
        // Leer el archivo y enviarlo al navegador
        readfile($archivo);
        exit;
    } else {
        echo 'El archivo no existe.';
    }
} else {
    echo 'Solicitud no válida.';
}
?>