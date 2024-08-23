<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/ProyectoWeb_PHP/controlador/conexion.php';
include_once 'registros.php';

$obj = new Conectar();
$obj3 = new Registro();
if (isset($_POST['filtro'])) {
    $accion = $_POST['filtro'];
    switch ($accion) {

        case 'Mostrar Excel':
            if (isset($_POST['tabla'])) {
                $_SESSION['tabla'] = $_POST['tabla']."";
                header('Location: ../index.php?excel&tabla='.$_SESSION['tabla']);
            }
            break;

        case 'Mostrar Errores':
            if (isset($_POST['tabla'])) {
                header('Location: ../index.php?filtro=1');
            }
            header('Location: ../index.php?filtro=1&tabla='.$_SESSION['tabla']);
            break;

        case 'Mostrar Todo':
            header('Location: ../index.php?filtro=2&tabla='.$_SESSION['tabla']);
            break;

        case 'Limpiar':
            header('Location: ../index.php?blank');
            break;

        case 'Mostrar Registros':
            if (isset($_POST['tabla'])) {
                $_SESSION['tabla'] = $_POST['tabla']."";
                if ($_SESSION['tabla'] !== "Seleccione_una_tabla") {
                        for ($i=1; $i <= 2; $i++) { 
                            $s = "Reporte".$i;
                            if (isset($_POST[$s]) && $_SESSION['tabla'] == $s) {
                                unset($_SESSION['registros']);
                                $_SESSION['registros'] = $obj->obtenerRegistrosPorFecha($_SESSION['tabla'],$_POST[$s]."");
                                $rpta = $_POST[$s]."";
                            }
                        }
                }
            }
            header('Location: ../index.php?filtro=3&tabla='.$_SESSION['tabla']."&orden=".$rpta."&pagina=1");
            break;
        default:
            echo "Acción no reconocida.";
            break;
    }

}else if (isset($_POST['COD'])) {
        $cod = $_POST['COD']."";
        $arrayUpdate = array();
        $arrayColumna = array();
        
        $arrayColumna = $obj->obtenerColumnas($_SESSION['tabla']."");
        $c = 0;
        $o = 0;
        $orden = "";
        foreach ($arrayColumna as $key => $value) {
            if ($value == "FOTO_Comprobante" || $value =="PDF_Comprobante" || $value == "COD") {
                unset($arrayColumna[$key]);
            }
        }
        foreach ($arrayColumna as $key => $value) {
            $o++;
            if ($value == "Fecha_compra") {
                break;
            }
        }
        do {
            $c++;
            if (isset($_POST[$c.""])) {
                if ($c == $o-2) {
                    $orden = substr($_POST[$o.""]."", 0, 7);
                }
                $valor = $_POST[$c.""];
                if ($valor == "") {
                    $valor = null;
                }else {
                    /* $valor = utf8_decode($valor); */
                    $valor = utf8_decode(htmlspecialchars($valor));
                }
                $arrayUpdate[] = $valor;
                
            }
        } while ($c <= count($arrayColumna));
        /* echo count($arrayColumna)."-------";
        echo count($arrayUpdate)."<br><table>";
        echo "<tr><td class='cabecera'>" . implode("</td><td>", $arrayColumna ). '</td></tr>';
        echo "<tr><td class='cabecera'>" . implode("</td><td>", $arrayUpdate) . '</td></tr>';echo "</table>"; */
        $obj->actualizarRegistro($arrayColumna,$arrayUpdate, $cod, $_SESSION['tabla']."");
        unset($_SESSION['registros']);
        $_SESSION['registros'] = $obj->obtenerRegistrosPorFecha($_SESSION['tabla']."",$orden);
        if (isset($_SESSION['pagina'])) {
            header('Location: ../index.php?filtro=3&'.$_SESSION['tabla']."&orden=".$orden."&pagina=".$_SESSION['pagina']);
        }else {
            header('Location: ../index.php?filtro=3&'.$_SESSION['tabla']."&orden=".$orden."&pagina=1");
        }
        
}

?>