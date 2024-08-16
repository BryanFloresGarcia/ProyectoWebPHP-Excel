<?php
class Conectar
{
     function conectar()
     {
          $serverName = "DESKTOP-7QPRHNG\SQLEXPRESS"; //serverName\instanceName
          $connectionInfo = array("Database" => "probando", "UID" => "usuarioPHP", "PWD" => "1234", "Encrypt" => "no");
          $conn = sqlsrv_connect($serverName, $connectionInfo);

          //if( $conn ) {
          //     echo "Conexión establecida.<br />";
          //}else{
          //     echo "Conexión no se pudo establecer.<br />";
          //     die( print_r( sqlsrv_errors(), true));
          //}
          return $conn;
     }
     function crearTabla($nombre)
     {
          try {
               $conn = Conectar::conectar();
               $tsql = "SELECT OBJECT_ID('dbo.$nombre', 'U') AS TableID";
               $consulta = sqlsrv_query($conn, $tsql);
               $row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
               $tableExiste = ($row['TableID'] == null) ? true : false;

               if ($tableExiste) {
                    $tsql = "CREATE TABLE " . $nombre . " (COD INT IDENTITY(1,1) PRIMARY KEY)";
                    $crear = sqlsrv_query($conn, $tsql);
                    if ($crear == FALSE) {
                         echo ("Error! Creando Tabla " . $nombre);
                         die(print_r(sqlsrv_errors(), true));
                    }
               }
               sqlsrv_close($conn);
          } catch (Exception $e) {
               echo ("Error!");
               die(print_r(sqlsrv_errors(), true));
          }
     }
     function insertarColumnas($array, $tabla)
     {
          try {
               $conn = Conectar::conectar();
               foreach ($array as $item) {
                    //echo $item . "\n";
                    $tsql = "SELECT " . $item . " FROM " . $tabla;
                    $tipoDato = "";
                    $consulta = sqlsrv_query($conn, $tsql);

                    if ($consulta == FALSE) {
                         switch ($item) {
                              case strpos($item, 'Caja_Numero') !== false:
                                   $tipoDato = "VARCHAR(20)";
                                   break;
                              case strpos($item, 'Project_Desc') !== false || strpos($item, 'Rubro_compra') !== false || strpos($item, 'Fecha_compra') !== false:
                                   $tipoDato = "VARCHAR(50)";
                                   break;
                              case strpos($item, 'Tipo_Comprobante') !== false:
                                   $tipoDato = "CHAR(1)";
                                   break;
                              case strpos($item, 'Moneda') !== false:
                                   $tipoDato = "VARCHAR(15)";
                                   break;
                              case strpos($item, 'Precio') !== false || strpos($item, 'Caja_Ant') !== false || strpos($item, 'Caja_rel_ent') !== false || strpos($item, 'Caja_rel_sal') !== false || strpos($item, 'Caja_Geosac') !== false:
                                   $tipoDato = "DECIMAL(10, 2)";
                                   break;
                              case strpos($item, 'Mes') !== false:
                                   $tipoDato = "INT";
                                   break;
                              default:
                                   $tipoDato = "VARCHAR(255)";
                                   break;
                         }
                         $tsql = "ALTER TABLE " . $tabla . " ADD " . $item . " " . $tipoDato;
                         $insertar = sqlsrv_query($conn, $tsql);
                         if ($insertar == FALSE)
                              echo ("Error! Agregando: " . $item . "<br>");
                    }

               }
               //echo ("Error! Agregando: ".$tsql);
               sqlsrv_close($conn);
          } catch (Exception $e) {
               echo ("Error!");
          }
     }
     function eliminarColumnas($array, $tabla)
     {
          try {
               $conn = Conectar::conectar();
               foreach ($array as $item) {
                    //echo $item . "\n";
                    $tsql = "ALTER TABLE " . $tabla . " DROP COLUMN " . $item;
                    $insertar = sqlsrv_query($conn, $tsql);
                    if ($insertar == FALSE)
                         echo ("Error! Eliminando: " . $item);


               }

               sqlsrv_close($conn);
          } catch (Exception $e) {
               echo ("Error!");
          }
     }
     function escribirCampos($arrayColumna, $arrayDatos, $tabla)
     {
          $arrayValores = array();
          try {
               $conn = Conectar::conectar();
               $columnas = "";
               $valores = "";
               
               $contador = 0;
               foreach ($arrayColumna as $campo) {
                    //echo ("variable campo: " . $campo);
                    if ($contador == 0) {
                         $contador += 1;
                         $columnas = "" . $campo;
                    } else {
                         $columnas = $columnas . ", " . $campo;
                    }
               }
               foreach ($arrayDatos as $item) {
                    $i = 0;
                    foreach ($item as $campo) {
                         if ($campo == 'vacio') {
                              $campo = NULL;
                         }
                         //echo ("variable campo: " . $campo);
                         $valores = $valores . "?,";
                         $arrayValores[$i] = $campo;
                         $i += 1;
                    }
                    $valores = substr($valores, 0, -1);
                    $sql = "INSERT INTO " . $tabla . " (" . utf8_decode($columnas) . ") VALUES (" . utf8_decode($valores) . ")";
                    echo ("Agregando: " . $sql);
                    // Prepara y ejecuta la declaración
                    $stmt = sqlsrv_query($conn, $sql, $arrayValores);
                    if ($stmt === false) {
                         die(print_r(sqlsrv_errors(), true));
                    }
                    $valores = "";
               }
               
               sqlsrv_close($conn);


          } catch (Exception $e) {
               echo ("Error!");
          }
     }

}
?>