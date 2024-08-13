<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/SimpleXLSX.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/controlador/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/llamadas/upload.php';

use Shuchkin\SimpleXLSX;

class Archivo
{
    function obtenerDatos(string $archivo)
    {
        //$obj = new Conectar();
        if ($xlsx = SimpleXLSX::parse($archivo)) {

            $header_values = $rows = [];
            foreach ($xlsx->rows() as $k => $r) {
                if ($k === 0) {
                    $header_values = $r;
                    continue;

                }
                for ($i = 0; $i < count($r); $i++) {
                    if (empty($r[$i])) {
                        $r[$i] = "vacio";
                    }
                    if (strpos($r[$i], ".jpg") !== false) {
                        $r[$i] = substr($r[$i], 10);
                    }
                }
                //print_r($r);
                $rows[] = array_combine($header_values, $r);
            }
            return $rows;
            //$obj->insertarColumnas($rows[0]);
        }
    }
    function obtenerCabecera(string $archivo)
    {
        //$obj = new Conectar();
        if ($xlsx = SimpleXLSX::parse($archivo)) {

            $header_values = $rows = [];
            foreach ($xlsx->rows() as $k => $r) {
                $header_values = $r;
                $rows[] = array_combine($header_values, $r);
                break;
            }
            return $rows;
            //$obj->insertarColumnas($rows[0]);
        }
    }
    function escribirDatos(string $archivo)
    {
        //$obj = new Conectar();
        if ($xlsx = SimpleXLSX::parse($archivo)) {

            $header_values = $rows = [];
            foreach ($xlsx->rows() as $k => $r) {
                if ($k === 0) {
                    $header_values = $r;
                    $rows[] = array_combine($header_values, $r);
                    continue;
                }

            }
            return $rows[0];
            //$obj->insertarColumnas($rows[0]);
        }
    }
    function subirArchivo()
    {
        $target_dir = "datos/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        // Check if file already exists
        if (file_exists($target_file)) {
            echo "El archivo ya existe. ";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "xlsx" && $imageFileType != "xls" && $imageFileType != "xlsm"
        ) {
            echo "Lo siento, el tipo de archivo no esta permitido.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "No se ha subido el archivo";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "El archivo " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " ha sido subido al servidor.";
            } else {
                echo "Ocurrió un error al subir el archivo.";
            }
        }
        //unlink($Your_file_path);
        return $target_file;
    }
    function mostrarExcel(string $archivo)
    {

        if ($xlsx = SimpleXLSX::parse($archivo)) {
            echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';
            foreach ($xlsx->rows() as $r) {
                for ($i = 0; $i < count($r); $i++) {
                    if (strpos($r[$i], ".jpg") !== false) {
                        $r[$i] = "<img src='img".substr($r[$i], 11)."' width='300' height='200'>".substr($r[$i], 12);
                    }
                }
                echo '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';
            }
            echo '</table>';
        } else {
            echo SimpleXLSX::parseError();
        }

    }
//EN CONSTRUCCION...........................................................................
    function moverImagenes($origen, $destino) {
        // Crear la carpeta de destino si no existe
        if (!is_dir($destino)) {
            mkdir($destino, 0755, true);
        }
    
        // Abrir la carpeta de origen
        $dir = opendir($origen);
    
        while (($archivo = readdir($dir)) !== false) {
            if ($archivo != '.' && $archivo != '..') {
                $rutaOrigen = $origen . '/' . $archivo;
                $rutaDestino = $destino . '/' . $archivo;
    
                if (is_dir($rutaOrigen)) {
                    // Si es un directorio, mover el contenido recursivamente
                    Archivo::moverImagenes($rutaOrigen, $rutaDestino);
                    rmdir($rutaOrigen); // Eliminar directorio vacío
                } else {
                    // Mover archivo
                    rename($rutaOrigen, $rutaDestino);
                }
            }
        }
    }

}
?>