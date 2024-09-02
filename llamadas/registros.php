<?php
class Registro
{
    function mostrarRegistros($arrayRegistros, $filtro)
    {
        //sort($arrayRegistros);
        
        usort($arrayRegistros, fn($a, $b) => $b['COD'] <=> $a['COD']);
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
                /* echo "<tr><td style='background-color: red'>" . implode("</td><td style='background-color: red'>", $r) . '</td></tr>'; */
                echo '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';
            } else if ($filtro >= 2) {
                echo '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';
            }

        }
        echo '</table>';
    }

    function mostrarRegistrosPorFecha($arrayRegistros, $filtro, $cant, $indice)
    {
        if (isset($arrayRegistros[0]["Fecha_compra"])) {
            usort($arrayRegistros, fn($a, $b) => $a['Fecha_compra'] <=> $b['Fecha_compra']);
        } elseif((isset($arrayRegistros[0]["Fecha1"]))){
            usort($arrayRegistros, fn($a, $b) => $a['Fecha1'] <=> $b['Fecha1']);
        }else {
            usort($arrayRegistros, fn($a, $b) => $a['Fecha'] <=> $b['Fecha']);
        }
        $k = 0;
        $p = 0;
        $lim = 0;
        echo '<br><table border="1" cellpadding="3" style="border-collapse: collapse">';
        foreach ($arrayRegistros as $r) {
            $lim++;

            if ($lim < $indice && $cant !== 0) {
                $cant++;
                continue;
            }
            $contador = 0;
            foreach ($r as $a => $valor) {

                if ($k == 0) {
                    foreach ($r as $columna => $val) {
                        if ($columna == "COD") {
                            //columnas[] = " name=$columna>Acción"; 
                            unset($r[$a]);
                        }else {
                            $columnas[] = " name=$columna>".$columna;
                        }
                    }
                    echo "<tr><td class='cabecera'" . implode("</td><td class='cabecera'", $columnas) . '</td></tr>';
                    $k++;
                }

                $r[$a] = utf8_encode($valor);
                if (strpos($valor, ".jpg") !== false || strpos($valor, ".jpeg") !== false || strpos($valor, ".png") !== false) {
                    $r[$a] = "<a href='comprobantes/" . $valor . "'>comprobante</a>";
                } else if (strpos($valor, ".pdf") !== false) {
                    $r[$a] = "<a href='comprobantes/" . $valor . "'>comprobante</a>";
                } else if ($a == "Serie_comprobante") {
                    if (($r[$a] == "" /* || strlen($r[$a]) > 4 */) && $p !== 2) {
                        $p = 1;
                    }
                } else if ($a == "RUC_Numero") {
                    if (($r[$a] == "" || strlen($r[$a]) > 11 || strlen($r[$a]) < 11) && $p !== 2) {
                        $p = 1;
                    }
                } else if ($a == "COD") {
                    //$r[$a] = "<form method='POST' action='llamadas/procesarRegistro.php'><input name='COD' type='text' value='".$r[$a]."' hidden><button type='submit' id='submitCod' name='accion' class='cod'>Modificar</button>";
                    //$contador--;
                    unset($r[$a]);
                } else if ($a =="Tipo_Comprobante") {
                    if ($valor == "R") {
                        $p = 2;
                    }
                }
                /* if ($a !== "FOTO_Comprobante" && $a !== "COD" && $a !== "PDF_Comprobante") {
                    $r[$a] = "<textarea id=$a name=$contador rows='2' cols='20'>".$r[$a]."</textarea>";
                } */
                
                $contador++;
            }

            if ($p == 1) {
                $p = 0;
                if ($filtro >= 2) {
                    echo "<tr><td>" . implode('</td><td>', $r) . "</td></tr></form>";
                    $contador=0;
                }else {
                    echo "<tr><td style='background-color: red'>" . implode("</td><td style='background-color: red'>", $r) . "</td></tr></form>";
                }

            }else if ($filtro >= 2) {
                echo "<tr><td>" . implode("</td><td>", $r) . "</td></tr></form>";
                $contador=0;
                
            }  
            $p = 0;
            if ($lim == $cant && $cant !== 0) {
                break;
            }

        }
        echo '</form></table>';
    }
    function escribirOpciones($datos, $tabla, $opc1, $id, $orden)
    {
            $partes = explode('_', $tabla);
            if (count($partes) == 2 && $tabla !== "REGISTROS_RCE") {
                $tabla = $partes[0];
                $name = $partes[1];
            }elseif (count($partes) == 3) {
                $tabla = $partes[0]."_".$partes[1];
                $name = $partes[2];
            }else {
                $name = $tabla;
            }
        
        switch ($opc1) {
            case 0:
                foreach ($datos as $valor => $texto) {
                    if ($texto == $orden) {
                        echo "<option value=\"$texto\"  selected='selected'>" . $texto . "</option>";
                    } else {
                        echo "<option value=\"$texto\">" . $texto . "</option>";
                    }
                }
                break;
            case 1:
                echo "<label id='L".$tabla."-".$id."' hidden></label><select id='".$tabla."-".$id."' name='".$name."' style='font-size:20px; width: 230px;'>";
                Registro::escribirOpciones($datos,$tabla,0,0,$orden);
                echo "</select>";
                break;
            case 2:
                echo "<label id='L".$tabla."-".$id."' hidden></label><select id='".$tabla."-".$id."' name='".$name."' style='font-size:20px; width: 230px;' hidden>";
                Registro::escribirOpciones($datos,$tabla,0,0,$orden);
                echo "</select>";
                break;
            case 3:
                echo "<select id='".$tabla."' name='".$name."' style='font-size:20px; width: 230px;' hidden>";
                echo "<option value=''>Seleccione una tabla</option>";
                echo "</select>";
                break;
            
            default:
                echo "<select id='".$tabla."' name='".$name."' style='font-size:20px; width: 230px;'>";
                echo "<option value=''>Seleccione una tabla</option>";
                echo "</select>";
                break;
            
        }

    }

}