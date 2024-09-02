<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/SimpleXLSX.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/controlador/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/llamadas/upload.php';

use Shuchkin\SimpleXLSX;
$uploadOk = 0;
class Archivo
{
    function getUploadOK()
    {
        return $GLOBALS['uploadOk'];
    }
    function setUploadOK()
    {
        $GLOBALS['uploadOk'] = 2;
    }
    function obtenerDatos(string $archivo, $txt)
    {
        if ($txt == 1) {
            $registrosTotales = array();
            $contenido = file_get_contents($archivo);
            $lineas = explode("\n", trim($contenido));
            foreach ($lineas as $linea) {
                $campos = explode('|', utf8_decode($linea.""));
                $registrosTotales[] = $campos;
            }
            if ($_SESSION['nombreDeTabla'] == "REGISTROS_RCE") {
                $registrosTotales = array_map(function($array) {
                    return array_slice($array, 0, 40); // Obtiene los primeros 40 elementos
                }, $registrosTotales);
            }else {
                $registrosTotales = array_map(function($array) {
                    return array_slice($array, 0, 59); // Obtiene los primeros 40 elementos
                }, $registrosTotales);
            }
            
            return $registrosTotales;
        }else {
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
                            $r[$i] = NULL;
                        } else {
                            /* $r[$i] = utf8_decode($r[$i]); */
                        }

                        if (strpos($r[$i], ".jpg") !== false || strpos($r[$i], ".pdf") !== false || strpos($r[$i], ".png") !== false || strpos($r[$i], ".jpeg") !== false) {
                            $r[$i] = substr($r[$i], strpos($r[$i], '/') + 1);
                        }
                    }
                    //print_r($r);
                    $rows[] = array_combine($header_values, $r);
                }
                return $rows;
                //$obj->insertarColumnas($rows[0]);
            }
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
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/datos/";
        if (isset($_FILES["fileToUpload"]["name"])) {
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        }
        if (isset($_FILES["fileCustom"]["name"])) {
            $target_file = $target_dir . basename($_FILES["fileCustom"]["name"]);
        }
        if (isset($_FILES["zipToUpload"]["name"])) {
            $target_file = $target_dir . basename($_FILES["zipToUpload"]["name"]);
        }
        if (isset($_FILES["archivoTxt1"]["name"])) {
            $target_file = $target_dir . basename($_FILES["archivoTxt1"]["name"]);
        }
        if (isset($_FILES["archivoTxt2"]["name"])) {
            $target_file = $target_dir . basename($_FILES["archivoTxt2"]["name"]);
        }
        $GLOBALS['uploadOk'] = 2;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {

            $GLOBALS['uploadOk'] = 0;
        }

        // Allow certain file formats
        if (isset($_FILES["fileToUpload"]["name"]) || isset($_FILES["fileCustom"]["name"])) {
            if (isset($_FILES["fileToUpload"]["name"])) {
                $nombreArchivo = "fileToUpload";
            } else {
                $nombreArchivo = "fileCustom";
            }

            if ($imageFileType != "xlsx" && $imageFileType != "xls" && $imageFileType != "xlsm") {

                $GLOBALS['uploadOk'] = 1;
            }

            if ($GLOBALS['uploadOk'] <= 1) {
                echo "No se ha subido el archivo";

            } else {
                if (move_uploaded_file($_FILES[$nombreArchivo]["tmp_name"], $target_file)) {
                    echo "El archivo " . htmlspecialchars(basename($_FILES[$nombreArchivo]["name"])) . " ha sido subido al servidor.";
                } else {

                    $GLOBALS['uploadOk'] = 10;
                }
            }
        }
        if (isset($_FILES["archivoTxt1"]["name"]) || isset($_FILES["archivoTxt2"]["name"])) {
            if (isset($_FILES["archivoTxt1"]["name"])) {
                $nombreArchivo = "archivoTxt1";
            } else {
                $nombreArchivo = "archivoTxt2";
            }

            if (
                $imageFileType != "txt"
            ) {

                $GLOBALS['uploadOk'] = 1;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($GLOBALS['uploadOk'] <= 1) {
                echo "No se ha subido el archivo";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES[$nombreArchivo . ""]["tmp_name"], $target_file)) {
                    echo "El archivo " . htmlspecialchars(basename($_FILES[$nombreArchivo . ""]["name"])) . " ha sido subido al servidor.";
                } else {

                    $GLOBALS['uploadOk'] = 10;
                }
            }
        }

        if (isset($_FILES["zipToUpload"]["name"])) {
            if (
                $imageFileType != "zip"
            ) {
                $GLOBALS['uploadOk'] = 1;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($GLOBALS['uploadOk'] <= 1) {
                echo "No se ha subido el archivo";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["zipToUpload"]["tmp_name"], $target_file)) {
                    echo "El archivo " . htmlspecialchars(basename($_FILES["zipToUpload"]["name"])) . " ha sido subido al servidor.";
                    $GLOBALS['uploadOk'] = 3;
                } else {
                    $GLOBALS['uploadOk'] = 10;
                }
            }
        }

        //unlink($Your_file_path);
        return $target_file;
    }

    function mostrarExcel(string $archivo)
    {
        $cabecera = 0;
        if ($xlsx = SimpleXLSX::parse($archivo)) {
            echo '<br><table border="1" cellpadding="3" style="border-collapse: collapse">';
            foreach ($xlsx->rows() as $r) {
                for ($i = 0; $i < count($r); $i++) {
                    if (strpos($r[$i], ".jpg") !== false || strpos($r[$i], ".jpeg") !== false || strpos($r[$i], ".png") !== false) {
                        $r[$i] = "<a href='comprobantes/" . substr($r[$i], 11) . "'>comprobante</a>";
                    } else if (strpos($r[$i], ".pdf") !== false) {
                        $r[$i] = "<a href='comprobantes/" . substr($r[$i], 12) . "'>comprobante</a>";
                    }
                }
                if ($cabecera == 0) {
                    $cabecera++;
                    echo "<tr><td style='background-color: green'>" . implode("</td><td style='background-color: green'>", $r) . '</td></tr>';
                } else {
                    echo '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';
                }

            }
            echo '</table>';
        } else {
            echo SimpleXLSX::parseError();
        }

    }

    /* function mostrarExcel(string $archivo)
    {

        if ($xlsx = SimpleXLSX::parse($archivo)) {
            echo '<br><table border="1" cellpadding="3" style="border-collapse: collapse">';
            $tabla = $xlsx->rows();
            
            foreach ($tabla as $fila) {

                for ($i=0; $i < count($fila); $i++) { 
                    if ($fila[$i]=='Numero_comprobante'){
                        unset($fila[$i]);
                        break;
                    }
                }
            }
            $arrayIndice = array();
            $num = 0;
            foreach ($tabla as $r) {
                
                if ($num == 0) {
                    $n = count($r);
                    for ($i=0; $i < $n; $i++) { 
                        if ($r[$i]!=='Numero_comprobante'){
                            if ($r[$i]!=='Project_Desc'){
                                if ($r[$i]!=='Fecha_compra'){
                                    if ($r[$i] !== "FOTO_Comprobante" && $r[$i] !== "PDF_Comprobante"){
                                        $arrayIndice[] = $i;
                                        unset($r[$i]);
                                    }
                                }
                            }
                        }
                        $num++;
                    }
                }
                for ($i = 0; $i < $n; $i++) {
                    for ($j=0; $j < count($arrayIndice); $j++) {
                        if ($arrayIndice[$j]==$i) {
                            unset($r[$i]);
                        }
                    }
                    if (isset($r[$i])) {
                        if (strpos($r[$i], ".jpg") !== false || strpos($r[$i], ".jpeg") !== false || strpos($r[$i], ".png") !== false) {
                            $r[$i] = "<a href='comprobantes/" . substr($r[$i], 11) . "'>comprobante</a>";
                        }else if (strpos($r[$i], ".pdf") !== false){
                            $r[$i] = "<a href='comprobantes/".substr($r[$i], 12)."'>comprobante</a>";
                        }
                    }
                }
                 echo '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';   
            }
            echo '</table>';
        } else {
            echo SimpleXLSX::parseError();
        }

    } */

    function descomprimirZip($archivo)
    {
        $zipFile = $archivo;
        // Directorio donde se descomprimirá el contenido
        $extractTo = "datos/";

        // Crear una instancia de ZipArchive
        $zip = new ZipArchive;

        // Abrir el archivo ZIP
        if ($zip->open($zipFile) === TRUE) {
            // Extraer el contenido
            $zip->extractTo($extractTo);
            // Cerrar el archivo ZIP
            $zip->close();
            //echo 'Archivo descomprimido exitosamente.';
            // Obtener la lista de archivos extraídos
            $extractedFiles = [];
            $dir = new RecursiveDirectoryIterator($extractTo);
            $iterator = new RecursiveIteratorIterator($dir);

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $extractedFiles[] = $file->getPathname();
                }
            }
            // Mostrar las rutas de los archivos descomprimidos
            foreach ($extractedFiles as $filePath) {
                $path = str_replace('\\', '/', $filePath);
                // Dividir la cadena en partes usando el delimitador '/'
                $parts = explode('/', $path);
                // Construir la nueva cadena con las primeras dos partes
                $contDiagonal = substr_count($path, '/');
                if ($contDiagonal >= 2) {
                    $newPath = implode('/', array_slice($parts, 0, 2));
                    //echo "Archivo descomprimido: $newPath\n";
                    return $newPath;
                }
            }
        } else {
            echo 'No se pudo abrir el archivo ZIP.';
        }
    }

    function moverImagenes($sourceDir, $destDir)
    {
        $from = $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/" . $sourceDir;
        $to = $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/" . $destDir;

        //echo "DESDE: ".$from . "  HACIA  ". $to;

        //Abro el directorio que voy a leer
        $dir = opendir($from);

        //Recorro el directorio para leer los archivos que tiene
        while (($file = readdir($dir)) !== false) {
            //Leo todos los archivos excepto . y ..
            if (strpos($file, '.') !== 0) {
                //Copio el archivo manteniendo el mismo nombre en la nueva carpeta
                // Crea el directorio de destino si no existe
                if (!is_dir($to)) {
                    if (!mkdir($to, 0777, true)) {
                        echo "No se pudo crear el directorio de destino.";
                        return;
                    }
                }
                copy($from . '/' . $file, $to . '/' . $file);
            }
        }
        // Elimina el directorio fuente y su contenido
        Archivo::borrarDirectorio($from);

        echo "Directorio movido con éxito.";
    }
    function borrarDirectorio($directorio)
    {
        // Verifica si el directorio existe
        if (!file_exists($directorio)) {
            return false;
        }

        // Si es un directorio, elimina los archivos y subdirectorios recursivamente
        if (is_dir($directorio)) {
            $items = array_diff(scandir($directorio), array('.', '..'));

            foreach ($items as $item) {
                $ruta = $directorio . DIRECTORY_SEPARATOR . $item;
                if (is_dir($ruta)) {
                    Archivo::borrarDirectorio($ruta); // Llama recursivamente si es un subdirectorio
                } else {
                    unlink($ruta); // Elimina el archivo
                }
            }

            rmdir($directorio); // Elimina el directorio vacío
        } else {
            // Si no es un directorio, simplemente elimina el archivo
            unlink($directorio);
        }

        return true;
    }

}


?>