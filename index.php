<!DOCTYPE html>
<fo="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Proyecto WEB</title>
        <h1>Importación de Excel a BD SQL</h1>

        <link rel="stylesheet" type="text/css" href="css/estilos.css" media="all">
        <script src="js/script.js"></script>
        <?php
        session_start();
        include_once 'llamadas/archivo.php';
        include_once 'llamadas/registros.php';
        include_once 'controlador/conexion.php';

        $obj = new Conectar();
        $obj2 = new Archivo();
        $obj3 = new Registro();
        $rutaArchivo = "";
        $respuesta = "";
        ?>

    </head>


    <body>
        <br><br>
        <div>
            <div style="display: flex; flex-wrap: wrap; font-size: 20px;">
                <div style="padding-right: 40px; padding-bottom: 20px">
                    <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formExcel">
                        <label>Seleccione el archivo Excel</label>

                        <input type="file" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx" class="btnImportar"
                            style="font-size:15px;">
                        <button type="submit" id="submit" name="import" class="my-button" hidden>Importar
                            Excel</button>
                    </form>
                </div>
                <div style="padding-bottom: 30px;">
                    <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formExcel2">
                        <label>Seleccione el archivo Excel de Sunat</label>

                        <input type="file" name="excelToUpload" id="excelToUpload" accept=".xls,.xlsx"
                            class="btnImportar" style="font-size:15px;">
                        <button type="submit" id="submit2" name="import" class="my-button" hidden>Importar
                            Excel</button>
                    </form>
                </div>

                <form style="display: flex; flex-wrap: wrap; font-size: 20px;" method="POST"
                    action="llamadas/procesarRegistro.php">

                    <div style="padding-left: 30px;  padding-bottom: 30px;">
                        <button type="submit" name="filtro" class="btnExcel" value="Mostrar Excel">Mostrar
                            Excel</button>
                    </div>

                    <div style="padding-left: 40px; padding-bottom: 30px;">
                        <button type="submit" name="filtro" class="btnErrores" value="Mostrar Errores">Mostrar
                            Errores</button>
                    </div>

                    <div style="padding-left: 30px;  padding-bottom: 30px;">
                        <button type="submit" name="filtro" class="btnSubidos" value="Mostrar Todo">Mostrar
                            todo</button>
                    </div>

                    <div style="padding-left: 30px;  padding-bottom: 30px;">
                        <button type="submit" name="filtro" class="btnLimpio" value="Limpiar">Limpiar</button>
                    </div>

                    <div style="padding-left: 40px; padding-bottom: 30px;">
                        <button id="btnMostrar" type="submit" name="filtro" class="btnMostrar"
                            value="Mostrar Registros">Mostrar
                            Registros</button>

                        <label for="categoria">Tabla:</label>
                        <select id="tabla" name="tabla" style="font-size:20px; width: 200px;">
                            <option value="Seleccione_una_tabla">Seleccione una tabla</option>
                            <?php
                            $orden = 0;
                            if (isset($_REQUEST['Reporte1'])) {
                                $orden = 1;
                                $tablita = 'Reporte1';
                            } else if (isset($_REQUEST['Reporte2'])) {
                                $orden = 2;
                                $tablita = 'Reporte2';
                            } else {
                                $tablita = "";
                            }
                            $tablas = $obj->obtenerTablas();
                            sort($tablas);
                            // Generar dinámicamente las opciones del select
                            foreach ($tablas as $valor => $texto) {
                                if (($orden == 1 && $texto == "Reporte1") || ($orden == 2 && $texto == "Reporte2")) {
                                    echo "<option value=\"$texto\"  selected='selected'>" . $texto . "</option>";
                                } else {
                                    echo "<option value=\"$texto\">" . $texto . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <label for="subcategoria">Ordenado por:</label>
                        <?php
                        if (isset($_REQUEST['orden'])) {
                            $orden = $_REQUEST['orden'];
                        } else {
                            $orden = "COD";
                        }
                        if (isset($_REQUEST['Reporte1'])) {
                            $_SESSION['tabla'] = 'Reporte1';
                            $obj3->escribirOpciones($obj->obtenerColumnas("Reporte1"), 1, 0, $orden);
                            $obj3->escribirOpciones($obj->obtenerColumnas("Reporte2"), 4, 0, $orden);
                        } elseif (isset($_REQUEST['Reporte2'])) {
                            $_SESSION['tabla'] = 'Reporte2';
                            $obj3->escribirOpciones($obj->obtenerColumnas("Reporte1"), 3, 0, $orden);
                            $obj3->escribirOpciones($obj->obtenerColumnas("Reporte2"), 2, 0, $orden);
                        } else {
                            $obj3->escribirOpciones($obj->obtenerColumnas("Reporte1"), 3, 1, $orden);
                            $obj3->escribirOpciones($obj->obtenerColumnas("Reporte2"), 4, 0, $orden);
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="my-formZIP" style="display: flex; flex-wrap: wrap; font-size: 20px;">
            <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data" id="formZIP">
                <div>
                    <label>Seleccione el archivo ZIP con las imágenes</label>

                    <input type="file" name="zipToUpload" id="zipToUpload" accept=".zip" class="btnImportar"
                        style="font-size:15px;">
                    <button type="submit" id="subir" name="import" class="my-button" hidden>Subir
                        Imágenes</button>
                </div>
            </form>
        </div><br>
        <?php
        /* -------------------------------------------------------------------------- */
        /*                           EJECUCION DE FUNCIONES                           */
        /* -------------------------------------------------------------------------- */
        if (!isset($_REQUEST['blank'])) {
            if (isset($_REQUEST['respuesta'])) {
                $respuesta = $_REQUEST['respuesta'];
                if ($respuesta >= 1) {
                    if (isset($_SESSION['data'])) {
                        $archivo = $_SESSION['data'];
                        //echo 'Valor recibido: ' . htmlspecialchars($archivo);
                        switch ($_SESSION['rpta']) {
                            case 0:
                                echo "El archivo ya existe. No se ha subido el archivo. ";
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
                                    echo "Los registros se han añadido con éxito. Mostrando registros añadidos desde la BD <br>";
                                    $arrayRegistros = $obj->obtenerRegistros($tabla, count($arrayDatos), 0);
                                    $obj3->mostrarRegistros($arrayRegistros, 2, "");
                                    unset($_SESSION['registros']);
                                    $_SESSION['tabla'] = $tabla;
                                    $_SESSION['registros'] = $arrayRegistros;
                                    $_SESSION['rutaArchivo'] = $archivo;
                                } else if ($respuesta == 2) {

                                    $tabla = 'Reporte2';
                                    $obj->crearTabla($tabla);
                                    $arrayColumna = $obj2->obtenerCabecera($archivo);
                                    $obj->insertarColumnas($arrayColumna[0], $tabla);
                                    $arrayDatos = $obj2->obtenerDatos($archivo);
                                    $obj->escribirCampos($arrayColumna[0], $arrayDatos, $tabla);
                                    $_SESSION['rpta'] = 4;
                                    echo "Los registros se han añadido con éxito. Mostrando registros añadidos desde la BD <br>";
                                    $arrayRegistros = $obj->obtenerRegistros($tabla, count($arrayDatos), 0);
                                    $obj3->mostrarRegistros($arrayRegistros, 2, "");
                                    unset($_SESSION['registros']);
                                    $_SESSION['tabla'] = $tabla;
                                    $_SESSION['registros'] = $arrayRegistros;
                                    $_SESSION['rutaArchivo'] = $archivo;
                                }
                                break;
                            case 3:
                                $archivo = $_SESSION['zip'];
                                $sourceDir = $obj2->descomprimirZip($archivo);
                                $obj2->moverImagenes($sourceDir, 'comprobantes');
                                echo "Archivo ZIP subido con éxito.";
                                break;
                            case 4:
                                //$_SESSION = array();
                                unset($_SESSION['rpta']);
                                unset($_SESSION['data']);
                                //header('Location: index.php');
                                break;
                            default:
                                echo "Ocurrió un error al subir el archivo.";
                        }
                        echo '<br><br>';
                    }
                }

                /* -------------------------------------------------------------------------- */
                /*                           Constructores de Tabla                           */
                /* -------------------------------------------------------------------------- */

            } else if (isset($_REQUEST['filtro'])) {
                $filtro = $_REQUEST['filtro'];
                if (isset($_SESSION['tabla']) && !isset($_REQUEST['Seleccione_una_tabla'])) {
                    $t = $_SESSION['tabla'];
                    if (isset($_REQUEST['tabla'])) {
                        echo "Mostrando registros de la tabla: " . $_REQUEST['tabla'] . " <br>";
                    } else {
                        echo "Mostrando registros de la tabla: " . $t . " <br>";
                    }

                } else {
                    if ($filtro == 1 && !isset($_REQUEST['Seleccione_una_tabla'])) {
                        if (isset($_SESSION['registros'])) {
                            echo "Mostrando registros con Errores. <br>";
                        } else {
                            echo "No hay datos que mostrar.";
                        }

                    } else if (!isset($_REQUEST['Seleccione_una_tabla'])) {
                        if (isset($_REQUEST['Reporte1']) || isset($_REQUEST['Reporte1'])) {
                            echo "Mostrando registros consultados desde la base de datos. <br>";
                        } else {
                            echo "No hay datos que mostrar.";
                        }

                    }

                }
                if (isset($_SESSION['registros'])) {
                    $ordenar = "COD";
                    if (isset($_REQUEST['orden'])) {
                        $ordenar = $_REQUEST['orden'];
                        if ($ordenar == NULL) {
                            $ordenar = "";
                        }
                    }

                    //Paginador de registros
                    if (!isset($_REQUEST['Seleccione_una_tabla'])) {
                        if (isset($_REQUEST['pagina']) || intval($_REQUEST['filtro']) == 3) {
                            $total = $obj->totalDeRegistros();
                            $paginas = round($total / 100);
                            if ($total / 100 > $paginas || $paginas == 1) {
                                $paginas++;
                            }
                            $lim = 0;
                            if ($paginas > 15) {
                                $lim = 15;
                            } else {
                                $lim = $paginas;
                            }
                            $a = intval($_REQUEST['pagina']);
                            $x = 0;
                            $y = 0;
                            for ($i = 1; $i < $lim; $i++) {
                                if ($a-- > 0 && $x < $lim / 2) {
                                    $x++;
                                } else {
                                    break;
                                }
                            }
                            $a = intval($_REQUEST['pagina']);

                            for ($i = 0; $i < $lim; $i++) {
                                if ($a + $i < $paginas && $x + $y < $lim) {
                                    $y++;
                                } else if ($x + $y < $lim) {
                                    $x++;
                                } else {
                                    break;
                                }
                            }

                            if ($lim == 15) {
                                $y++;
                            }
                            $a = intval($_REQUEST['pagina']);

                            echo "<div class='btnAS_div'>";

                            //echo $x . " ---------" .$y."<br>";
        
                            if (intval($_REQUEST['pagina']) !== 1) {
                                echo "<a class='numPagina' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=" . intval($_REQUEST['pagina']) - 1 . "'><</a>";
                            } else {
                                echo "<a class='numPagina'><</a>";

                            }


                            if ($a > 8 && $paginas > 15) {
                                echo "<a class='numPagina' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=1'>1</a>";
                                echo "<a class='numPagina'>...</a>";

                            }

                            if ($paginas > 15) {
                                for ($i = $a - $x + 1; $i < $y + $a; $i++) {

                                    if (intval($_REQUEST['pagina']) == $i) {
                                        echo "<a class='numSelect' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=$i'>$i</a>";
                                    } else {
                                        echo "<a class='numPagina' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=$i'>$i</a>";
                                    }
                                }
                            } else {
                                for ($i = 1; $i <= $paginas; $i++) {
                                    if (intval($_REQUEST['pagina']) == $i) {
                                        echo "<a class='numSelect' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=$i'>$i</a>";
                                    } else {
                                        if (round($paginas) == 2 && $total < 100) {
                                            break;
                                        }
                                        echo "<a class='numPagina' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=$i'>$i</a>";
                                    }
                                }
                            }
                            if ($paginas > $a + 7 && $paginas > 15) {
                                echo "<a class='numPagina'>...</a>";
                                echo "<a class='numPagina' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=$paginas'>$paginas</a>";
                            }

                            if (intval($_REQUEST['pagina']) !== intval($paginas) && $total > 100) {
                                echo "<a class='numPagina' href='index.php?filtro=$filtro&$t&orden=$ordenar&pagina=" . intval($_REQUEST['pagina']) + 1 . "'>></a>";
                            } else {
                                echo "<a class='numPagina'>></a>";
                            }
                            echo "</div>";
                            if (isset($_REQUEST['pagina'])) {
                                if (intval($_REQUEST['pagina']) > 1) {
                                    $_SESSION['registros'] = $obj->obtenerRegistros($_SESSION['tabla'], 100, 100 * (intval($_REQUEST['pagina']) - 1));
                                } else {
                                    if (intval($_REQUEST['pagina']) <= 4) {
                                        $_SESSION['registros'] = $obj->obtenerRegistros($_SESSION['tabla'], 100, 1);
                                    }
                                }
                            }
                        }
                        $obj3->mostrarRegistros($_SESSION['registros'], $filtro, $ordenar);
                    }
                }
            } else {
                if (isset($_SESSION['rutaArchivo'])) {
                    if (file_exists($_SESSION['rutaArchivo'])) {
                        echo "Mostrando Excel con los últimos registros añadidos.<br>";
                        $obj2->mostrarExcel($_SESSION['rutaArchivo']);
                    }
                }

            }
        }

        /* -------------------------------------------------------------------------- */
        /*                           Ejecutor de Macro Excel                          */
        /* -------------------------------------------------------------------------- */
        if (isset($_SESSION['tabla'])) {
            if (isset($_REQUEST['excel']) && $_SESSION['tabla'] !== "Seleccione_una_tabla") {
                exec("start excel " . $_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/MACRO_EXCEL_" . $_SESSION['tabla']);
                # code...
            } else {
                echo "No es posible mostrar el contenido, seleccione una Tabla primero.";
            }
        }
        /* -------------------------------------------------------------------------- */
        /*                                    ----                                    */
        /* -------------------------------------------------------------------------- */
        ?>
    </body>
    <footer>
        <script>
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
            document.getElementById('tabla').addEventListener('change', function (event) {
                activarSelectReporte();
            });
            document.getElementById('Reporte1').addEventListener('change', function (event) {
                activarSelectReporte();
            });
            document.getElementById('Reporte2').addEventListener('change', function (event) {
                activarSelectReporte();
            });
        </script>
    </footer>

    </html>