<?php
class Upload
{

  function subirArchivo()
  {
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/datos/";
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
      $imageFileType != "xlsx" && $imageFileType != "xls" && $imageFileType != "xlsm" && $imageFileType != "txt"
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
  function procesoEnEjecucion($nombreProceso) {
    // Ejecutar el comando `tasklist` para obtener la lista de procesos
    $comando = 'tasklist /FI "IMAGENAME eq ' . escapeshellarg($nombreProceso) . '"';
    $resultado = shell_exec($comando);
    
    // Si el resultado contiene el nombre del proceso, está en ejecución
    return strpos($resultado, $nombreProceso) !== false;
}

}
?>