<!DOCTYPE html>
<fo="es">

    <head>
        <title>Proyecto WEB</title>
        <h1>Importación de Excel a BD SQL</h1>

        <link rel="stylesheet" type="text/css" href="css/estilos.css" media="all">
    </head>


    <body>
        <?php
        include_once 'llamadas/archivo.php';
        include_once 'controlador/conexion.php';
        ?>
        <br><br>
        <div class="outer-container">
            <form method="POST" action="index.php" enctype="multipart/form-data" id="formExcel">

                <div>
                    <label>Seleccione el archivo Excel</label>

                    <input type="file" name="fileToUpload" id="fileToUpload" accept=".xls,.xlsx" class="btnImportar"
                        oneclick="activarSubmit()">
                    <button type="submit" id="submit" name="import" class="my-button" hidden="true">Importar
                        Excel</button>

                </div>
                <div id="response" class="<?php if (!empty($type)) {
                    echo $type . " display-block";
                } ?>">
                    <?php if (!empty($message)) {
                        echo $message;
                    } ?>
                </div>

            </form>

            <?php

            $obj = new Conectar();
            $obj2 = new Archivo();


            if (array_key_exists('import', $_POST)) {
                $archivo = "" . $obj2->subirArchivo();
                $obj2->mostrarExcel($archivo);/*
                $arrayColumna = $obj2->obtenerCabecera($archivo);
                $obj->insertarColumnas($arrayColumna[0]);
                $arrayDatos = $obj2->obtenerDatos($archivo);
                $obj->escribirCampos($arrayColumna[0], $arrayDatos);*/
            }
            ?>
        </div>

    </body>
    <footer>
        <script type="text/javascript">
            document.getElementById('fileToUpload').addEventListener('change', function (event) {
                // Llama a tu función cuando se selecciona un archivo
                activarSubmit();
            });


            function activarSubmit() {
                var elemento = document.getElementById('submit');
                elemento.hidden = false;

            }

            function desactivarSubmit() {
                var elemento = document.getElementById('submit');
                elemento.hidden = true;

            }

            var modal = document.getElementById('myModal');

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = document.getElementById('myImg');
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            img.onclick = function () {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }



        </script>
    </footer>

    </html>