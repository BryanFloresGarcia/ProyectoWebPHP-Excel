<!DOCTYPE html>
<fo="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Proyecto WEB</title>
        <link rel="stylesheet" type="text/css" href="css/estilos.css" media="all">
        <script src="js/script.js"></script>
        <section>
            <img src="img/Captu.png" alt="">
        </section><br>


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
        <fieldset>
            <ul id="tabs">
                <li><a id="tab1" class="tab1" href="">Importar</a></li>
                <!-- <li><a id="tab2" class="tab2" href="">Exportar</a></li> -->
            </ul>
            <div id="container">
                <div id="content1" class="content1"></a><br>
                    <h1>Importación de Excel a BD SQL</h1>

                    <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formExcel">
                        <div class="btnImportar">
                            <label>Importar: </label><input style="font-size: 16px; font-weight: bold;" type="text" name="nombreTabla">
                            <input style="cursor: pointer; font-size:15px;" type="file" name="fileCustom" id="fileCustom"
                                accept=".xls,.xlsx,.xlsm">
                        </div>
                        <button type="submit" id="submit" name="import" class="boton azul" >Importar
                            Excel</button>
                    </form>

                    <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formExcel">
                        <div class="btnImportar">
                        <label>Importar (COMPRAS/DEPOSITOS)</label>
                        <input style="cursor: pointer; font-size:15px;" type="file" name="fileToUpload" id="fileToUpload"
                            accept=".xls,.xlsx"></div>
                        <button type="submit" id="submit" name="import" class="boton azul" >Importar
                            Excel</button>
                    </form>

                    <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formTxt1">
                        <div class="btnImportar">
                        <label>Importar (REGISTRO RCE)</label>
                        <input type="file" name="archivoTxt1" id="archivoTxt1" accept=".txt"
                             style="cursor: pointer; font-size:15px;"></div>
                        <button style="cursor: pointer;" type="submit" id="submit2" name="import" class="boton azul"
                            >Importar
                            Archivo</button>
                    </form>

                    <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formTxt2">
                        <div class="btnImportar">
                        <label>Importar (PROPUESTA)</label>
                        <input type="file" name="archivoTxt2" id="archivoTxt2" accept=".txt"
                             style="cursor: pointer; font-size:15px;"></div>
                        <button style="cursor: pointer;" type="submit" id="submit3" name="import" class="boton azul"
                            >Importar
                            Archivo</button>
                    </form>

                    <!-- <form method="POST" action="llamadas/procesarArchivo.php" enctype="multipart/form-data"
                        id="formZIP">
                        <div>
                            <label>Seleccione el archivo ZIP con las imágenes</label>

                            <input type="file" name="zipToUpload" id="zipToUpload" accept=".zip" class="btnImportar"
                            style="cursor: pointer; font-size:15px;">
                            <button type="submit" id="subir" name="import" class="boton azul" hidden>Subir
                                Imágenes</button>
                        </div>
                    </form> -->
                </div>

                <div id="content2" class="content2" style="display: none; flex-wrap: wrap; font-size: 20px;">
                    <h1 style="font-size: 40px;">Exportación de Reportes</h1><br>
                    <div style="width: 1200px;">
                        <form method="POST" action="llamadas/procesarRegistro.php">
                            <input id="RTabla" name="RTabla" type="text" value="" hidden>
                            <input id="Rorden" name="Rorden" type="text" value="" hidden> Monto Recibido:
                            <input style="width: 120px; height: 25px; font-size: 20px; font-weight: bold;" value="0.00"
                                id="RMonto" name="RMonto" type="number" oninput="validarNumero(this)">
                            <input id="RProyecto" name="RProyecto" type="text" value="" hidden>
                            <label style="margin-left: 10px;">Empezar desde el día:</label>
                            <input id="RDia" name="RDia" type="number" value="0" max="31" min="0" oninput="validarEntrada(event)">
                            <button type="submit" name="download" formaction="llamadas/descarga.php" onclick="validarFormulario(event)" class="boton verde"
                                value="true">Generar Reporte</button>
                        </form>
                    </div>
                </div>

            </div><br>
            <fieldset><label id="botonera">Visualización</label>
                <form style="display: flex; flex-wrap: wrap; font-size: 20px;" method="POST"
                    action="llamadas/procesarRegistro.php">
                    <div style="padding: 20px 0px; padding-left: 20px;">
                        <label for="categoria">Tabla:</label>
                        <select id="tabla" name="tabla" style="font-size:20px; width: 230px;">
                            <option value="Seleccione_una_tabla">Seleccione una tabla</option>
                            <?php
                            $orden = 0;
                            $tablas = $obj->obtenerTablas();
                            sort($tablas);
                            if (isset($_SESSION['tabla'])) {
                                $nombreTabla = $_SESSION['tabla'];
                            } else {
                                $nombreTabla = "Seleccione_una_tabla";
                            }
                            // Generar dinámicamente las opciones del select
                            if (isset($_REQUEST['tabla'])) {
                                foreach ($tablas as $key => $nombre) {
                                    if ($nombre == $nombreTabla) {
                                        echo "<option value=\"$nombre\"  selected='selected'>" . $nombre . "</option>";
                                    } else {
                                        echo "<option value=\"$nombre\">" . $nombre . "</option>";
                                    }
                                }
                            } else {
                                foreach ($tablas as $key => $nombre) {
                                    echo "<option value=\"$nombre\">" . $nombre . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <label for="subcategoria">Año-Mes:</label>
                        <?php
                        if (isset($_REQUEST['orden'])) {
                            $orden = $_REQUEST['orden'];
                        } else {
                            $orden = "COD";
                        }
                        $numeroId = 0;
                        if (isset($_REQUEST['tabla'])) {
                            $nomTabla = $_REQUEST['tabla'];

                            foreach ($tablas as $key => $tab) {
                                $fechas = $obj->obtenerAnioyMes($tab);
                                if ($obj->validarTabla($nomTabla) && $nomTabla !== "Seleccione_una_tabla") {
                                    $_SESSION['tabla'] = $nomTabla;
                                    if ($nomTabla == $tab) {
                                        $obj3->escribirOpciones($fechas, $tab, 1, $numeroId, $orden);
                                    } else {
                                        $obj3->escribirOpciones($fechas, $tab, 2, $numeroId, $orden);
                                    }
                                } else {
                                    $obj3->escribirOpciones($fechas, $tab, 2, $numeroId, $orden);
                                }
                                foreach ($fechas as $key => $value) {
                                    $obj3->escribirOpciones($obj->obtenerProyecto($value, $tab), $tab . "_P" . $value, 2, $value, $orden);
                                    
                                }
                                $numeroId++;
                            }
                            if ($nomTabla == "Seleccione_una_tabla") {
                                $obj3->escribirOpciones(0, "ordenarVacio", 4, $numeroId, $orden);
                            } else {
                                $obj3->escribirOpciones(0, "ordenarVacio", 3, $numeroId, $orden);
                            }
                        } else {

                            $obj3->escribirOpciones(0, "ordenarVacio", 4, $numeroId, $orden);
                            foreach ($tablas as $key => $tab) {
                                $fechas = $obj->obtenerAnioyMes($tab);
                                if ($obj->validarTabla($tab)) {
                                    $obj3->escribirOpciones($obj->obtenerAnioyMes($tab), $tab, 2, $numeroId, $orden);
                                    foreach ($fechas as $key => $value) {
                                        $obj3->escribirOpciones($obj->obtenerProyecto($value, $tab), $tab . "_P" . $value, 2, $value, $orden);
                                        
                                    }
                                    $numeroId++;
                                }
                            }
                        }
                        ?>
                        <br><br>
                        <div style="margin-left: 30px;">
                            <button style="margin-left: 25px;" id="btnMostrar" type="submit" name="filtro"
                                class="boton azul" value="Mostrar Registros">Ver
                                Registros</button>
                            <button id="btnRojo" type="submit" name="filtro" class="boton rojo"
                                value="Mostrar Errores">Errores</button>
                            <button id="btnGris" type="submit" name="filtro" class="boton gris"
                                value="Limpiar">Limpiar</button>
                        </div>
                    </div>
                </form>
            </fieldset>
        </fieldset>
        <?php
        /* -------------------------------------------------------------------------- */
        /*                           EJECUCION DE FUNCIONES                           */
        /* -------------------------------------------------------------------------- */
        if (isset($_REQUEST['orden'])) {
            $ordenar = $_REQUEST['orden'];
            $_SESSION['orden'] = $ordenar;
            if ($ordenar == NULL) {
                $ordenar = "";
            }
        }
        if (!isset($_REQUEST['blank'])) {
            if (isset($_REQUEST['respuesta']) && !isset($_REQUEST['excel'])) {
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
                                echo "Lo siento, el tipo de archivo no esta permitido o no ha sido seleccionado.";
                                unset($_SESSION['nombreDeTabla']);
                                break;
                            case 2:
                                if ($respuesta == 1 && isset($_SESSION['nombreDeTabla'])) {
                                    $tabla = $_SESSION['nombreDeTabla']."";
                                    $obj->crearTabla($tabla);
                                    if ($tabla == "REGISTROS_RCE") {
                                        $arrayColumna = $obj2->obtenerCabecera($_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/plantilla_Registro_RCE.xlsx");
                                        $arrayDatos = $obj2->obtenerDatos($archivo,1);
                                    }else if ($tabla == "PROPUESTA") {
                                        $arrayColumna = $obj2->obtenerCabecera($_SERVER['DOCUMENT_ROOT'] . "/ProyectoWeb_PHP/plantilla_Propuesta.xlsx");
                                        $arrayDatos = $obj2->obtenerDatos($archivo,1);
                                    }else {
                                        $arrayColumna = $obj2->obtenerCabecera($archivo);
                                        $arrayDatos = $obj2->obtenerDatos($archivo,0);
                                    }
                                    $obj->insertarColumnas($arrayColumna[0], $tabla);
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

            } else if (isset($_REQUEST['filtro']) && !isset($_REQUEST['excel'])) {
                $filtro = intval($_REQUEST['filtro']);
                if (isset($_REQUEST['tabla'])) {
                    if (isset($_SESSION['tabla']) && $_REQUEST['tabla'] !== "Seleccione_una_tabla" && $filtro !== 1) {
                        $nombreTabla = $_SESSION['tabla'];
                        if (isset($_REQUEST['tabla'])) {
                            echo "Mostrando registros de la tabla: " . $_REQUEST['tabla'] . " <br>";
                        } else {
                            echo "Mostrando registros de la tabla: " . $t . " <br>";
                        }

                    } else {
                        if ($filtro == 1 && $_REQUEST['tabla'] !== "Seleccione_una_tabla") {
                            if (isset($_SESSION['registros'])) {
                                echo "Mostrando registros con Errores. <br>";
                            } else {
                                echo "No hay datos que mostrar.";
                            }

                        } else if ($_REQUEST['tabla'] !== "Seleccione_una_tabla") {
                            if (isset($_REQUEST['Reporte1']) || isset($_REQUEST['Reporte1'])) {
                                echo "Mostrando registros consultados desde la base de datos. <br>";
                            } else {
                                echo "No hay datos que mostrar.";
                            }

                        }

                    }
                    /* -------------------------------------------------------------------------- */
                    /*                           Paginador de registros                           */
                    /* -------------------------------------------------------------------------- */
                    if (isset($_SESSION['registros'])) {
                        if ($_REQUEST['tabla'] !== "Seleccione_una_tabla") {
                            if (intval($_REQUEST['filtro']) == 3) {
                                if (isset($_REQUEST['pagina'])) {
                                    //$total = $obj->totalDeRegistros();
                                    $maxPaginas = 50;
                                    $total = count($_SESSION['registros']);
                                    $paginas = round($total / $maxPaginas);

                                    if ($total / $maxPaginas > $paginas || $paginas == 1) {
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

                                    if (intval($_REQUEST['pagina']) !== 1) {
                                        echo "<a class='numPagina' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=" . intval($_REQUEST['pagina']) - 1 . "'><</a>";
                                    } else {
                                        echo "<a class='numPagina'><</a>";

                                    }


                                    if ($a > 8 && $paginas > 15) {
                                        echo "<a class='numPagina' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=1'>1</a>";
                                        echo "<a class='numPagina'>...</a>";

                                    }

                                    if ($paginas > 15) {
                                        for ($i = $a - $x + 1; $i < $y + $a; $i++) {

                                            if (intval($_REQUEST['pagina']) == $i) {
                                                echo "<a class='numSelect' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=$i'>$i</a>";
                                            } else {
                                                echo "<a class='numPagina' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=$i'>$i</a>";
                                            }
                                        }
                                    } else {
                                        for ($i = 1; $i <= $paginas; $i++) {
                                            if (intval($_REQUEST['pagina']) == $i) {
                                                echo "<a class='numSelect' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=$i'>$i</a>";
                                            } else {
                                                if (round($paginas) == 2 && $total < 50) {
                                                    break;
                                                }
                                                echo "<a class='numPagina' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=$i'>$i</a>";
                                            }
                                        }
                                    }
                                    if ($paginas > $a + 7 && $paginas > 15) {
                                        echo "<a class='numPagina'>...</a>";
                                        echo "<a class='numPagina' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=$paginas'>$paginas</a>";
                                    }

                                    if (intval($_REQUEST['pagina']) !== intval($paginas) && $total > $maxPaginas) {
                                        echo "<a class='numPagina' href='index.php?filtro=$filtro&tabla=$nombreTabla&orden=$ordenar&pagina=" . intval($_REQUEST['pagina']) + 1 . "'>></a>";
                                    } else {
                                        echo "<a class='numPagina'>></a>";
                                    }
                                    echo "</div>";
                                }
                            }
                            if (isset($_REQUEST['pagina'])) {
                                $_SESSION['pagina'] = $_REQUEST['pagina'] . "";
                                if (intval($_REQUEST['pagina']) >= 1) {
                                    $obj3->mostrarRegistrosPorFecha($_SESSION['registros'], 2, 50, ((intval($_REQUEST['pagina']) - 1) * 50) + 1);
                                }
                            } else {
                                $obj3->mostrarRegistrosPorFecha($_SESSION['registros'], $filtro, 0, 0);
                            }
                        } else {
                            echo "No es posible mostrar el contenido, seleccione una Tabla primero.";
                        }
                    }
                }
                /* -------------------------------------------------------------------------- */
                /*                           Ejecutor de Macro Excel                          */
                /* -------------------------------------------------------------------------- */
            } /* else if (isset($_SESSION['rutaArchivo'])) {
                if (file_exists($_SESSION['rutaArchivo'])) {
                    echo "Mostrando Excel con los últimos registros añadidos.<br>";
                    $obj2->mostrarExcel($_SESSION['rutaArchivo']);
                }
            } */

        }
        /* -------------------------------------------------------------------------- */
        /*                                    ----                                    */
        /* -------------------------------------------------------------------------- */
        ?>
    </body>
    <footer>
        <script>
            
            /* document.getElementById('zipToUpload').addEventListener('change', function (event) {
                comprobarArchivo('zipToUpload');
            }); */
            document.getElementById('tabla').addEventListener('change', function (event) {
                activarSelectReporte();
            });
            var c = 0;
            var nombreFecha = "";var nombreProyecto = "";
            const selectTabla = document.getElementById('tabla');
            for (const option of selectTabla.options) {
                if (option.value !== "Seleccione_una_tabla") {
                    activarSelectReporte();
                    nombreFecha = option.value + "-" + c;
                    document.getElementById(nombreFecha).addEventListener('change', function (event) {
                        activarSelectReporte();
                    });
                    c++;
                    const fechaSelect = document.getElementById(nombreFecha);
                    if (fechaSelect) {
                        for (const opcFecha of fechaSelect.options) {
                            nombreProyecto = option.value + "-" + opcFecha.value;
                            document.getElementById(nombreProyecto).addEventListener('change', function (event) {
                                activarSelectReporte();
                            });
                        }
                    }
                }
            }
            const enlace1 = document.getElementById('tab1');
            enlace1.style.background = "#007730";
            /* const enlace2 = document.getElementById('tab2');
            var div1 = document.getElementById('content1');
            var div2 = document.getElementById('content2');
            activaDesactivaDiv(enlace1,enlace2);
            enlace1.addEventListener('click', function (event) {
                activaDesactivaPestana(enlace1,enlace2,div1,div2,'block');
            });
            enlace2.addEventListener('click', function (event) {
                activaDesactivaPestana(enlace2,enlace1,div2,div1,'flex');
            }); */
        </script>
    </footer>

    </html>