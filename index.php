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
        <div class="outer-container">
            <div style="display: flex; flex-wrap: wrap;">
            <div style="padding-right: 40px;">
                <form method="POST" action="llamadas/procesar.php" enctype="multipart/form-data" id="formExcel">

                    <label>Seleccione el archivo Excel</label>

                    <input type="file" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx" class="btnImportar">
                    <button type="submit" id="submit" name="import" class="my-button" hidden="true">Importar
                        Excel</button>

                </form>
            </div>
            <div>
                <form method="POST" action="llamadas/procesar.php" enctype="multipart/form-data" id="formExcel2">


                    <label>Seleccione el archivo Excel de Sunat</label>

                    <input type="file" name="excelToUpload" id="excelToUpload" accept=".xls,.xlsx" class="btnImportar">
                    <button type="submit" id="submit2" name="import" class="my-button" hidden="true">Importar
                        Excel</button>


                </form>
            </div>
            </div><br>

            <form method="POST" action="llamadas/procesar.php" enctype="multipart/form-data" id="formZIP">
                <div>
                    <label>Seleccione el archivo ZIP con las imágenes</label>

                    <input type="file" name="zipToUpload" id="zipToUpload" accept=".zip" class="btnImportar">
                    <button type="submit" id="subir" name="import" class="my-button" hidden="true">Subir
                        Imágenes</button>

                </div>
            </form>
            <?php
            //EJECUCION DE FUNCIONES------------------------------------------------------
            
            $obj = new Conectar();
            $obj2 = new Archivo();
            if (isset($_REQUEST['respuesta'])) {
                $respuesta = $_REQUEST['respuesta'];

                if ($respuesta > 1) {
                    if (isset($_SESSION['data'])) {
                        $archivo = $_SESSION['data'];
                        ;
                        //echo 'Valor recibido: ' . htmlspecialchars($archivo);
                        switch ($_SESSION['rpta']) {
                            case 0:
                                echo "El archivo ya existe. ";
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
                                    $obj->escribirCampos($arrayColumna[0], $arrayDatos,$tabla);
                                } else {
                                    $tabla = 'Reporte2';
                                    $obj->crearTabla($tabla);
                                    $arrayColumna = $obj2->obtenerCabecera($archivo);
                                    $obj->insertarColumnas($arrayColumna[0], $tabla);
                                    $arrayDatos = $obj2->obtenerDatos($archivo);
                                    $obj->escribirCampos($arrayColumna[0], $arrayDatos,$tabla);
                                    
                                }
                                break;
                            case 3:
                                echo "Archivo ZIP cargado.";
                                break;
                            // Puedes agregar más casos según sea necesario
                            default:
                                echo "Ocurrió un error al subir el archivo.";
                        }
                        echo '<br><br>';
                        $obj2->mostrarExcel($archivo);


                    } else {
                        echo 'No se recibió ningún dato.';
                    }

                    //$obj2->descomprimirZip();
                }
            }

            //---------------------------------------------------------------------------           
            ?>
        </div>

    </body>
    <footer>
        <script type="text/javascript">
            document.getElementById('fileToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                activarSubmit(1);
            });
            document.getElementById('zipToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                activarSubir();
            });
            document.getElementById('excelToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                activarSubmit(2);
            });


            function activarSubmit($submit) {
                if ($submit == 1) {
                     var elemento = document.getElementById('submit');
                elemento.hidden = false;
                }else{
                    var elemento = document.getElementById('submit2');
                    elemento.hidden = false;
                }
            }

            function activarSubir() {
                var elemento = document.getElementById('subir');
                elemento.hidden = false;

            }

        </script>
    </footer>

    </html>