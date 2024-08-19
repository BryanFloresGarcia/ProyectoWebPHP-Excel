<?php
class Registro
{
    function mostrarRegistros($arrayRegistros, $filtro, $orden)
    {
        //sort($arrayRegistros);
        if ($orden !== "") {
            usort($arrayRegistros, fn($a, $b) => $b[$orden] <=> $a[$orden]);
        }
        
        //print_r($arrayRegistros);
        $arrayErrores = array();
        $k = 0;
        $p = 0;
        echo '<br><table border="1" cellpadding="3" style="border-collapse: collapse">';
        foreach ($arrayRegistros as $r) {

            foreach ($r as $a => $valor) {
                if ($k == 0) {
                    foreach ($r as $columna => $val) {
                        $columnas[] = $columna;
                    }

                    echo "<tr><td style='background-color: green'>" . implode("</td><td style='background-color: green'>", $columnas) . '</td></tr>';
                    $k++;
                }
                $r[$a] = utf8_encode($valor);
                if (strpos($valor, ".jpg") !== false || strpos($valor, ".jpeg") !== false || strpos($valor, ".png") !== false) {
                    $r[$a] = "<a href='comprobantes/" . $valor . "'>comprobante</a>";
                } else if (strpos($valor, ".pdf") !== false) {
                    $r[$a] = "<a href='comprobantes/" . $valor . "'>comprobante</a>";
                } else if ($a == "Serie_comprobante") {
                    if ($r[$a] == "" || strlen($r[$a]) > 4) {
                        $p = 1;
                    }
                } else if ($a == "RUC_Numero") {
                    if ($r[$a] == "" || strlen($r[$a]) > 11 || strlen($r[$a]) < 11) {
                        $p = 1;
                    }
                }
            }
            if ($p == 1) {
                $p = 0;
                $arrayErrores[] = $r;
                echo "<tr><td style='background-color: red'>" . implode("</td><td style='background-color: red'>", $r) . '</td></tr>';
            } else if ($filtro >= 2) {
                echo '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';
            }

        }
        echo '</table>';
    }
    function escribirOpciones($columnas, $opc1, $opc2, $orden)
    {
        if ($opc1 == 0) {
            foreach ($columnas as $valor => $texto) {
                if ($texto == $orden) {
                    echo "<option value=\"$texto\"  selected='selected'>" . $texto . "</option>";
                } else {
                    echo "<option value=\"$texto\">" . $texto . "</option>";
                }
            }
        } else if ($opc1 == 1) {
            echo "<select id='ordenarVacio' name='ordenarVacio' style='font-size:20px; width: 200px;' hidden>";
            echo "<option value=''>Seleccione una tabla</option>";
            echo "</select>";
            echo "<select id='Reporte1' name='Reporte1' style='font-size:20px; width: 200px;'>";
            Registro::escribirOpciones($columnas,0,0,$orden);
            echo "</select>";
        } else if ($opc1 == 2) {
            echo "<select id='ordenarVacio' name='ordenarVacio' style='font-size:20px; width: 200px;' hidden>";
            echo "<option value=''>Seleccione una tabla</option>";
            echo "</select>";
            echo "<select id='Reporte2' name='Reporte2' style='font-size:20px; width: 200px;'>";
            Registro::escribirOpciones($columnas,0,0,$orden);
            echo "</select>";
        } else {
            if ($opc1 == 3) {
                if ($opc2 == 1) {
                    echo "<select id='ordenarVacio' name='ordenarVacio' style='font-size:20px; width: 200px;'>";
                    echo "<option value=''>Seleccione una tabla</option>";
                    echo "</select>";
                }
                echo "<select id='Reporte1' name='Reporte1' style='font-size:20px; width: 200px;' hidden>";
                Registro::escribirOpciones($columnas,0,0,$orden);
                echo "</select>";
            }else {
                echo "<select id='Reporte2' name='Reporte2' style='font-size:20px; width: 200px;' hidden>";
                Registro::escribirOpciones($columnas,0,0,$orden);
                echo "</select>";
            }
        }

    }

}