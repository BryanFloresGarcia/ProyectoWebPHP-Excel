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
                    if ($crear == FALSE)
                    {
                         echo ("Error! Creando Tabla ".$nombre);
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
                    $consulta = sqlsrv_query($conn, $tsql);
                    if ($consulta == FALSE) {
                         $tsql = "ALTER TABLE " . $tabla . " ADD " . $item . " VARCHAR(255)";
                         $insertar = sqlsrv_query($conn, $tsql);
                         if ($insertar == FALSE)
                              echo ("Error! Agregando: " . $item);
                    }

               }

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
                    //
                    foreach ($item as $campo) {
                         //echo ("variable campo: " . $campo);
                         if ($campo == "vacio") {
                              $campo = "";
                         }
                         if ($contador == 1) {
                              $contador += 1;
                              $valores = "" . $campo;
                         } else {
                              $valores = $valores . "', '" . $campo;
                         }
                    }

                    if ($contador == 2) {
                         $contador = $contador - 1;

                         $tsql = "INSERT INTO " . $tabla . " (" . $columnas . ") VALUES ('" . utf8_decode($valores) . "')";
                         $insertar = sqlsrv_query($conn, $tsql);
                         if ($insertar == FALSE)
                              echo ("Error! Agregando: ");// $valores);
                         //break;
                    }

               }
               sqlsrv_close($conn);


          } catch (Exception $e) {
               echo ("Error!");
          }
     }

}
?>