<!DOCTYPE html>
<fo="es">

    <head>
        <title>Proyecto WEB</title>
        <h1>Importación de Excel a BD SQL</h1>

        <link rel="stylesheet" type="text/css" href="css/estilos.css" media="all">
    </head>


    <body>
        <?php
        session_start();
        include_once 'llamadas/archivo.php';
        include_once 'controlador/conexion.php';
        ?>
        <br><br>
        <div>
            <div style="display: flex; flex-wrap: wrap;">
                <div style="padding-right: 40px; padding-bottom: 20px">
                    <form method="POST" action="llamadas/procesar.php" enctype="multipart/form-data" id="formExcel">
                        <label>Seleccione el archivo Excel</label>

                        <input type="file" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx"
                            class="btnImportar">
                        <button type="submit" id="submit" name="import" class="my-button" hidden="true">Importar
                            Excel</button>
                    </form>
                </div>
                <div>
                    <form method="POST" action="llamadas/procesar.php" enctype="multipart/form-data" id="formExcel2">
                        <label>Seleccione el archivo Excel de Sunat</label>

                        <input type="file" name="excelToUpload" id="excelToUpload" accept=".xls,.xlsx"
                            class="btnImportar">
                        <button type="submit" id="submit2" name="import" class="my-button" hidden="true">Importar
                            Excel</button>
                    </form>
                </div>
            </div>
            <div class="my-formZIP">
                <form method="POST" action="llamadas/procesar.php" enctype="multipart/form-data" id="formZIP">
                    <div>
                        <label>Seleccione el archivo ZIP con las imágenes</label>

                        <input type="file" name="zipToUpload" id="zipToUpload" accept=".zip" class="btnImportar">
                        <button type="submit" id="subir" name="import" class="my-button" hidden="true">Subir
                            Imágenes</button>

                    </div>
                </form>
            </div><br>
            <?php
            //EJECUCION DE FUNCIONES------------------------------------------------------
            
            $obj = new Conectar();
            $obj2 = new Archivo();
            $rutaArchivo = "";

            if (isset($_REQUEST['respuesta'])) {
                $respuesta = $_REQUEST['respuesta'];

                if ($respuesta >= 1) {
                    if (isset($_SESSION['data'])) {
                        $archivo = $_SESSION['data'];
                        ;
                        //echo 'Valor recibido: ' . htmlspecialchars($archivo);
                        switch ($_SESSION['rpta']) {
                            case 0:
                                echo "El archivo ya existe. No se ha subido el archivo. ";
                                if ($respuesta<>3) {
                                    $obj2->mostrarExcel($archivo);
                                }
                                
                                break;
                            case 1:
                                echo "Lo siento, el tipo de archivo no esta permitido.";
                                break;
                            case 2:
                                if ($respuesta == 1) {
                                    $tabla = 'Reporte1';
                                    $obj->crearTabla($tabla);
                                    $arrayColumna = $obj2->obtenerCabecera($archivo);
                                    $obj->insertarColumnas($arrayColumna[0], $tabla);
                                    $arrayDatos = $obj2->obtenerDatos($archivo);
                                    $obj->escribirCampos($arrayColumna[0], $arrayDatos, $tabla);
                                    $_SESSION['rpta'] = 4;
                                    echo "El archivo se ha subido con éxito. ";
                                } else if ($respuesta == 2){
                                    $tabla = 'Reporte2';
                                    $obj->crearTabla($tabla);
                                    $arrayColumna = $obj2->obtenerCabecera($archivo);
                                    $obj->insertarColumnas($arrayColumna[0], $tabla);
                                    $arrayDatos = $obj2->obtenerDatos($archivo);
                                    $obj->escribirCampos($arrayColumna[0], $arrayDatos, $tabla);
                                    $_SESSION['rpta'] = 4;
                                    echo "El archivo se ha subido con éxito. ";
                                }
                                break;
                            case 3:
                                $archivo = $_SESSION['zip'];
                                $sourceDir = $obj2->descomprimirZip($archivo);
                                $obj2->moverImagenes($sourceDir,'comprobantes');
                                echo "Archivo ZIP subido con éxito.";
                                break;
                            case 4:
                                $_SESSION = array();
                                $_SESSION['rutaArchivo'] = $archivo;
                                header('Location: index.php?respuesta=4');
                                break;
                            // Puedes agregar más casos según sea necesario
                            default:
                                echo "Ocurrió un error al subir el archivo.";
                        }
                        echo '<br><br>';
                        if ($respuesta == 1 || $respuesta == 2)
                            $obj2->mostrarExcel($archivo);
                    }

                    //$obj2->descomprimirZip();
                }
                if ($respuesta == 4) {
                    echo "Mostrando último archivo subido.";
                    $obj2->mostrarExcel($_SESSION['rutaArchivo']);
                }
            }

            //----------------------------------------------------------------------------           
            ?>
        </div>

    </body>
    <footer>
        <script type="text/javascript">
            document.getElementById('fileToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                comprobarArchivo('fileToUpload');
            });
            document.getElementById('zipToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                comprobarArchivo('zipToUpload');
            });
            document.getElementById('excelToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                comprobarArchivo('excelToUpload');
            });

            function activarSubmit($idArchivo) {
                if ($idArchivo == 'fileToUpload') {
                    var elemento = document.getElementById('submit');
                    elemento.hidden = false;
                } else if($idArchivo == 'excelToUpload'){
                    var elemento = document.getElementById('submit2');
                    elemento.hidden = false;
                }else{
                    var elemento = document.getElementById('subir');
                    elemento.hidden = false;
                }
            }
            
            function desactivarSubmit($idArchivo) {
                if ($idArchivo == 'fileToUpload') {
                    var elemento = document.getElementById('submit');
                    elemento.hidden = true;
                } else if($idArchivo == 'excelToUpload'){
                    var elemento = document.getElementById('submit2');
                    elemento.hidden = true;
                }else{
                    var elemento = document.getElementById('subir');
                    elemento.hidden = true;
                }
            }

            function mostrarrutaArchivo($idArchivo) {
                const archivoInput = document.getElementById($idArchivo);
                const rutaArchivo = archivoInput.files[0] ? archivoInput.files[0].name : 'Ningún archivo seleccionado';
                return rutaArchivo;
            }

            function comprobarArchivo($idArchivo) {
                // Define la URL del archivo en la carpeta "datos"
                
                    const url = 'datos/'+mostrarrutaArchivo($idArchivo); // Reemplaza con la URL del archivo
                if (url != 'datos/Ningún archivo seleccionado') {
                    fetch(url, { method: 'HEAD' }) // Usa el método HEAD para solo verificar la existencia
                        .then(response => {
                            if (response.ok) {
                                alert('El archivo ya existe.');
                                desactivarSubmit($idArchivo);
                                return false;
                            } else {
                                activarSubmit($idArchivo);
                                return true;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al comprobar el archivo.');
                        });
                }else{
                    alert('No se ha seleccionado el archivo.');
                }
            }
        

        </script>
    </footer>

    </html>