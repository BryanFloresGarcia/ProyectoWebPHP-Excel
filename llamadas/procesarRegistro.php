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
            header('Location: ../');
            break;

        case 'Mostrar Errores':
            if (isset($_POST['tabla'])) {
                header('Location: ../index.php?filtro=1&JOOO');
            }
            header('Location: ../index.php?filtro=1&'.$_SESSION['tabla']);
            break;

        case 'Mostrar Todo':
            header('Location: ../index.php?filtro=2&'.$_SESSION['tabla']);
            break;

        case 'Mostrar Registros':
            if (isset($_POST['tabla'])) {
                $_SESSION['tabla'] = $_POST['tabla']."";
                if ($_SESSION['tabla'] !== "Seleccione_una_tabla") {
                    if (!isset($_SESSION['registros'])) {
                        $_SESSION['registros'] = $obj->obtenerRegistros($_SESSION['tabla'],100,0);
                    }
                    for ($i=1; $i <= 2; $i++) { 
                        $s = "Reporte".$i;
                        if (isset($_POST[$s]) && $_SESSION['tabla'] == $s) {
                            $rpta = $_POST[$s]."";
                        }
                    }
                    if (!isset($rpta)){
                        $rpta = "COD";
                    }
                }
            }
            header('Location: ../index.php?filtro=3&'.$_SESSION['tabla']."&orden=".$rpta."&pagina=1");
            break;
        default:
            echo "Acción no reconocida.";
            break;
    }

}

/* 
if (isset($_POST['filtro1'])) {

header('Location: ../index.php?filtro=1&nofunciono');
}elseif (isset($_POST['filtro2'])) {
header('Location: ../index.php?filtro=2');
}elseif (isset($_POST['filtro3'])) {
if (isset($_POST['tabla'])) {
    $tabla = $_POST['tabla']."";
    if ($tabla !== "Seleccione una tabla") {
        $_SESSION['registros'] = $obj->obtenerRegistros($tabla,100);
        if (isset($_POST['Seleccionado'])) {
            $rpta = $_POST['Seleccionado']."";
        }
    }
    
}
header('Location: ../index.php?filtro=3&'.$tabla."&orden=".$rpta);
}else{
header('Location: ../');
} */

?>