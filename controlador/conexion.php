<?php
class Conectar
{
     function conectar()
     {
		  /* $serverName = "WIN-TP9OBC79B4E"; //serverName\instanceName
          $connectionInfo = array("Database" => "REPORTES", "UID" => "usuarioPHP", "PWD" => "@S0p0rt3", "Encrypt" => "no");
          $conn = sqlsrv_connect($serverName, $connectionInfo); */
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
                    sqlsrv_free_stmt($crear);
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
               $j = 0;
               foreach ($array as $item) {
                    //echo $item . "\n";
                    $tsql = "SELECT " . $item . " FROM " . $tabla;
                    $tipoDato = "";
                    $consulta = sqlsrv_query($conn, $tsql);

                    if ($consulta == FALSE) {
                         if ($tabla == "CAJA") {
                              if (strpos($item, 'Fecha') !== false || strpos($item, 'Caja_Numero') !== false) {
                                   $tipoDato = "VARCHAR(50)";
                              }else {
                                   $tipoDato = "DECIMAL(10, 2)";
                              }
                         }else {
                              switch ($item) {
                                   case strpos($item, 'Caja_Numero') !== false:
                                        $tipoDato = "VARCHAR(20)";
                                        break;
                                   case strpos($item, 'Project_Desc') !== false || strpos($item, 'Rubro_compra') !== false || strpos($item, 'Fecha_compra') !== false || strpos($item, 'Fecha') !== false:
                                        $tipoDato = "VARCHAR(50)";
                                        break;
                                   case strpos($item, 'Tipo_Comprobante') !== false:
                                        $tipoDato = "CHAR(1)";
                                        break;
                                   case strpos($item, 'Moneda') !== false:
                                        $tipoDato = "VARCHAR(15)";
                                        break;
                                   case strpos($item, 'IMPUESTO') !== false || strpos($item, '_Venta') !== false || strpos($item, 'Precio') !== false || strpos($item, 'Caja_Ant') !== false || strpos($item, 'Caja_rel_ent') !== false || strpos($item, 'Caja_rel_sal') !== false || strpos($item, 'Caja_Geosac') !== false:
                                        $tipoDato = "DECIMAL(10, 2)";
                                        break;
                                   case strpos($item, 'Mes') !== false:
                                        $tipoDato = "INT";
                                        break;
                                   default:
                                        $tipoDato = "VARCHAR(255)";
                                        if (strpos($item, 'Numero_comprobante') !== false) {
     
                                        }
                                        break;
                              }
                         }
                         
                         $j++;
                         $tsql = "ALTER TABLE " . $tabla . " ADD " . $item . " " . $tipoDato;
                         $insertar = sqlsrv_query($conn, $tsql);
                         if ($insertar == FALSE) {
                              echo ("Error! Agregando: " . $item . "<br>");
                         }
                         sqlsrv_free_stmt($insertar);
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
               sqlsrv_free_stmt($insertar);
               sqlsrv_close($conn);
          } catch (Exception $e) {
               echo ("Error!");
          }
     }
     function eliminarRegistros($arrayColumna, $arrayDatos, $tabla) {
          
          $conn = Conectar::conectar();
          $arrayValores = array();
          $i = 0; $fc = -1; $cn = 0; $pc = -1;$pro=0; $omitidos=0;
          $query = "SELECT FOTO_Comprobante, Caja_Numero, Project_Desc FROM " . $tabla;
          $stmt = sqlsrv_query($conn, $query);
          if ($stmt === false) {
               echo "ERROR: La tabla no existe. ";
          }else {
               try {
                    foreach ($arrayColumna as $campo) {
                         //echo ("variable campo: " . $campo);
                         if ($campo == "FOTO_Comprobante") {
                              if ($campo !== "" || $campo !== null ) {
                                   $fc = $i;
                              }
                              
                         }
                         if ($campo == "PDF_Comprobante") {
                              if ($campo !== "" || $campo !== null ) {
                                   $pc = $i;
                              }
                              
                         }
                         if ($campo == "Caja_Numero") {
                              $cn = $i;
                         }
                         if ($campo == "Project_Desc") {
                              $pro = $i;
                         }
                         $i++;
                    }
                    foreach ($arrayDatos as $item) {
                         $i = 0;
                         foreach ($item as $campo) {
                              //echo ("variable campo: " . $campo);
                              $arrayValores[$i] = $campo;
                              $i += 1;
                         }
                         if ($fc > -1) {
                              $sql = "DELETE FROM $tabla WHERE (FOTO_Comprobante IN ('".$arrayValores[$fc]."') AND Caja_Numero IN ('".$arrayValores[$cn]."') AND Project_Desc IN ('".$arrayValores[$pro]."'));";
                         }else if ($pc > -1) {
                              $sql = "DELETE FROM $tabla WHERE (PDF_Comprobante IN ('".$arrayValores[$pc]."') AND Caja_Numero IN ('".$arrayValores[$cn]."') AND Project_Desc IN ('".$arrayValores[$pro]."'));";
                         }
                         //echo ("Eliminado: " . $sql);
                         // Prepara y ejecuta la declaración
                         $stmt = sqlsrv_query($conn, $sql);
                         if ($stmt === false) {
                                   die(print_r(sqlsrv_errors(), true));
                         }
                         
                    }
               } catch (Exception $e) {
                    echo ("Error!");
               }
          }
     }
     function escribirCampos($arrayColumna, $arrayDatos, $tabla)
     {
          $conn = Conectar::conectar();
          $columnas = "";
          $arrayValores = array();
          $valores = "";
          $Numero_comprobante = -1;
          $contador = 0;
          $nomComprobante = "";
          $indiceComprobante = 0;
          $i = 0; $fc = -1; $cn = 0; $pc = -1;$pro=0; $omitidos=0;
          $query = "SELECT FOTO_Comprobante, Caja_Numero, Project_Desc FROM " . $tabla;
          $stmt = sqlsrv_query($conn, $query);
          $numRuc = "";

          if ($stmt === false) {

               try {
                    foreach ($arrayColumna as $campo) {
                         //echo ("variable campo: " . $campo);
                         if ($contador == 0) {
                              $contador += 1;
                              $columnas = "" . $campo;
                         } else {
                              $columnas = $columnas . ", " . $campo;
                         }
                         if ($campo == 'Numero_comprobante') {
                              $Numero_comprobante = $i;
                         }
                         $i++;
                    }
                    foreach ($arrayDatos as $item) {
                         $i = 0;
                         foreach ($item as $campo) {
                              //echo ("variable campo: " . $campo);
                              if ($Numero_comprobante == $i) {
                                   $campo = ltrim($campo, '0');
                                   $campo = str_pad($campo, 8, "0", STR_PAD_LEFT);
                                   if (strlen($campo) > 8) {
                                        $partes = explode('-', $campo);
                                        $campo = $partes[1];
                                   }
                              }

                              $valores = $valores . "?,";
                              if ($campo == "" || $campo == null) {
                                   if ($tabla == "CAJA" && $i !== 1) {
                                        $arrayValores[$i] = 0;
                                   }else {
                                        $arrayValores[$i] = $campo; 
                                   }
                                   
                              }else {
                                   $arrayValores[$i] = utf8_decode($campo);
                              }
                              $i += 1;
                         }
                         $valores = substr($valores, 0, -1);
                         $sql = "INSERT INTO " . $tabla . " (" . utf8_decode($columnas) . ") VALUES (" . $valores . ")";
                         //echo ("Agregando: " . $sql);
                         // Prepara y ejecuta la declaración
                         $stmt = sqlsrv_query($conn, $sql, $arrayValores);
                         if ($stmt === false) {
                              die(print_r(sqlsrv_errors(), true));
                         }
                         $valores = "";
                    }
               } catch (Exception $e) {
                    echo ("Error!");
               }
          } else {

               try {
                    foreach ($arrayColumna as $campo) {
                         //echo ("variable campo: " . $campo);
                         if ($contador == 0) {
                              $contador += 1;
                              $columnas = "" . $campo;
                         } else {
                              $columnas = $columnas . ", " . $campo;
                         }
                         if ($campo == 'Numero_comprobante') {
                              $Numero_comprobante = $i;
                         }
                         if ($campo == "FOTO_Comprobante") {
                              if ($campo !== "" || $campo !== null ) {
                                   $fc = $i;
                              }
                         }
                         if ($campo == "PDF_Comprobante") {
                              if ($campo !== "" || $campo !== null ) {
                                   $pc = $i;
                              }
                         }
                         if ($campo == "Caja_Numero") {
                              $cn = $i;
                         }
                         if ($campo == "Project_Desc") {
                              $pro = $i;
                         }
                         $i++;
                         $columnasUpdate[] = "$campo = ?";
                    }
                    $columnUpdate = implode(', ', $columnasUpdate);
                    foreach ($arrayDatos as $item) {
                         $i = 0;
                         foreach ($item as $campo) {
                              //echo ("variable campo: " . $campo);
                              if ($Numero_comprobante == $i) {
                                   $campo = ltrim($campo, '0');
                                   $campo = str_pad($campo, 8, "0", STR_PAD_LEFT);
                                   if (strlen($campo) > 8) {
                                        $partes = explode('-', $campo);
                                        $campo = $partes[1];
                                   }
                              }
                              $valores = $valores . "?,";
                              if ($campo == "" || $campo == null) {
                                   $arrayValores[$i] = $campo; 
                              }else {
                                   $arrayValores[$i] = utf8_decode($campo);
                              }
                              
                              $i += 1;
                         }
                         if ($fc > -1 && $arrayValores[$fc] !== "" && $arrayValores[$fc] !== null) {
                              $nomComprobante = "FOTO_Comprobante";
                              $indiceComprobante = $fc;
                              $consulta = "SELECT FOTO_Comprobante FROM " . $tabla . " WHERE FOTO_Comprobante = '".$arrayValores[$fc]."' AND Caja_Numero = '".$arrayValores[$cn]."' AND Project_Desc = '".$arrayValores[$pro]."';";
                         }else if ($pc > -1 && $nomComprobante == "") {
                              $nomComprobante = "PDF_Comprobante";
                              $indiceComprobante = $pc;
                              $consulta = "SELECT PDF_Comprobante FROM " . $tabla . " WHERE PDF_Comprobante = '".$arrayValores[$pc]."' AND Caja_Numero = '".$arrayValores[$cn]."' AND Project_Desc = '".$arrayValores[$pro]."';";
                         }
                         $consulta = "SELECT " . $nomComprobante . " FROM " . $tabla . " WHERE  " . $nomComprobante . " = '".$arrayValores[$indiceComprobante]."' AND Caja_Numero = '".$arrayValores[$cn]."' AND Project_Desc = '".$arrayValores[$pro]."';";
                         $stmt = sqlsrv_query($conn, $consulta);
                         if (sqlsrv_has_rows($stmt)) {
                              $valores = substr($valores, 0, -1);
                              $sql = "UPDATE ".$tabla." SET $columnUpdate WHERE " . $nomComprobante . " = '".$arrayValores[$indiceComprobante]."' AND Caja_Numero = '".$arrayValores[$cn]."' AND Project_Desc = '".$arrayValores[$pro]."';";
                              $nomComprobante = "";
                              $indiceComprobante = 0;
                              //echo ("Agregando: " . $sql);
                              // Prepara y ejecuta la declaración
                              $stmt = sqlsrv_query($conn, $sql, $arrayValores);
                              if ($stmt === false) {
                                   die(print_r(sqlsrv_errors(), true));
                              }
                              sqlsrv_free_stmt($stmt);
                         }else {
                              $valores = substr($valores, 0, -1);
                              $nomComprobante = "";
                              $indiceComprobante = 0;
                              /* if (strlen($numRuc) === 11) { */
                                   $sql = "INSERT INTO " . $tabla . " (" . utf8_decode($columnas) . ") VALUES (" . $valores . ")";
                                   //echo ("Agregando: " . $sql);
                                   // Prepara y ejecuta la declaración
                                   $stmt = sqlsrv_query($conn, $sql, $arrayValores);
                                   if ($stmt === false) {
                                        die(print_r(sqlsrv_errors(), true));
                                   }
                               /* } else {
                                   $omitidos++;
                               } */
                              
                         }
                         
                         $valores = "";
                    }
                    if ($omitidos > 0) {
                         echo "Se han omitido " . $omitidos . " registro(s). ";
                    }
               } catch (Exception $e) {
                    echo ("Error!");
               }
          }
          /* sqlsrv_free_stmt($stmt); */
          sqlsrv_close($conn);
     }
     function obtenerRegistros($tabla, $n, $cod)
     {
          $conn = Conectar::conectar();

          // Consulta para obtener los últimos n registros
          if ($cod >= 1) {
               if ($cod == 1) {
                    $cod = 0;
               }
               $consulta = "SELECT  * FROM " . $tabla . " ORDER BY COD DESC OFFSET " . $cod . " ROWS FETCH NEXT (?) ROWS ONLY;";
          } else {
               $consulta = "SELECT TOP (?) * FROM " . $tabla . " ORDER BY COD DESC;";
          }
          // Preparar y ejecutar consulta
          $params = [$n];
          $resultado = sqlsrv_query($conn, $consulta, $params);

          // Verificar si la consulta fue exitosa
          if ($resultado === false) {
               echo "Error en la consulta: ";
               die(print_r(sqlsrv_errors(), true));
          }

          // Obtener datos
          $registros = [];
          while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
               $registros[] = $fila;
          }

          // Liberar recursos y cerrar conexión
          sqlsrv_free_stmt($resultado);
          sqlsrv_close($conn);

          return $registros;
     }

     function obtenerRegistrosPorFecha($tabla, $fecha)
     {
          $elementos = explode('-', $fecha);
          $conn = Conectar::conectar();
          if ($tabla == "CAJA") {
               if ($fecha == "Todo" || $fecha == "" || $fecha == null) {
                    $consulta = "SELECT * FROM $tabla";
               }else {
                    $consulta = "SELECT * FROM $tabla WHERE FORMAT(CAST(Fecha AS DATE), 'yyyy-MM-dd') = ?";
               }
               
          }else {
               if (count($elementos) > 2) {
                    $consulta = "SELECT * FROM $tabla WHERE FORMAT(CAST(Fecha AS DATE), 'yyyy-MM-dd') = ?";
               } else {
                    if ($tabla == "REGISTROS_RCE") {
                         $consulta = "SELECT * FROM $tabla WHERE FORMAT(CAST(Fecha1 AS DATE), 'yyyy-MM') = ?";
                    } else {
                         $consulta = "SELECT * FROM $tabla WHERE FORMAT(CAST(Fecha_compra AS DATE), 'yyyy-MM') = ?";
                    }
               }
          }
          
          // Preparar y ejecutar consulta
          $params = [$fecha];
          if ($tabla == "CAJA") {
               $resultado = sqlsrv_query($conn, $consulta);
          }else {
               $resultado = sqlsrv_query($conn, $consulta, $params);
          }

          // Verificar si la consulta fue exitosa
          if ($resultado === false) {
               echo "Error en la consulta: ";
               die(print_r(sqlsrv_errors(), true));
          }

          // Obtener datos
          $registros = [];
          while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
               $registros[] = $fila;
          }

          // Liberar recursos y cerrar conexión
          sqlsrv_free_stmt($resultado);
          sqlsrv_close($conn);

          return $registros;
     }

     function obtenerTablas()
     {
          $conn = Conectar::conectar();
          $query = "SELECT * FROM sys.tables";
          $stmt = sqlsrv_query($conn, $query);

          if ($stmt === false) {
               die(print_r(sqlsrv_errors(), true));
          }

          $tablas = array();

          // Recorrer los resultados y llenar el array
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
               $tablas[] = $row['name'];
          }
          sqlsrv_free_stmt($stmt);
          sqlsrv_close($conn);
          return $tablas;
     }

     function validarTabla($tabla)
     {
          $conn = Conectar::conectar();
          $query = "SELECT TOP (1) * FROM " . $tabla;
          $stmt = sqlsrv_query($conn, $query);

          if ($stmt === false) {
               sqlsrv_close($conn);
               return false;
          } else {
               sqlsrv_free_stmt($stmt);
               sqlsrv_close($conn);
               return true;
          }

     }
     function obtenerColumnas($nombreTabla)
     {
          $conn = Conectar::conectar();

          // Consulta para obtener nombres de columnas
          $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?";
          $params = array($nombreTabla);
          $stmt = sqlsrv_query($conn, $query, $params);

          if ($stmt === false) {
               die(print_r(sqlsrv_errors(), true));
          }
          $columnas = array();

          // Recorrer los resultados y llenar el array
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
               $columnas[] = $row['COLUMN_NAME'];
          }
          sqlsrv_free_stmt($stmt);
          sqlsrv_close($conn);
          return $columnas;
     }
     function totalDeRegistros()
     {
          $conn = Conectar::conectar();
          // Consultar el número total de registros
          $sql_total = "SELECT COUNT(*) AS total FROM Reporte1";
          if ($sql_total === false) {
               die(print_r(sqlsrv_errors(), true));
          }
          $result_total = sqlsrv_query($conn, $sql_total);
          $row = sqlsrv_fetch_array($result_total, SQLSRV_FETCH_ASSOC);
          sqlsrv_free_stmt($result_total);
          sqlsrv_close($conn);
          return $row['total'];

     }
     function obtenerAnioyMes($tabla)
     {

          $conn = Conectar::conectar();
          // Consulta SQL para extraer y formatear la fecha
          $sql = "
          SELECT DISTINCT TOP 12 
          FORMAT(CAST(Fecha_compra AS DATE), 'yyyy-MM') AS FechaFormateada
          FROM $tabla ORDER BY FechaFormateada DESC";

          // Ejecutar la consulta
          $stmt = sqlsrv_query($conn, $sql);

          // Verificar la ejecución de la consulta
          if ($stmt === false) {
               $sql = "SELECT DISTINCT TOP 12 
               FORMAT(CAST(Fecha AS DATE), 'yyyy-MM-dd') AS FechaFormateada
               FROM " . $tabla . " ORDER BY FechaFormateada DESC";
               $stmt = sqlsrv_query($conn, $sql);
               if ($stmt === false) {
                    $sql = "SELECT DISTINCT TOP 12 
                    FORMAT(CAST(Fecha1 AS DATE), 'yyyy-MM') AS FechaFormateada
                    FROM " . $tabla . " ORDER BY FechaFormateada DESC";
                    $stmt = sqlsrv_query($conn, $sql);
                    if ($stmt === false) {
                         $fechas = ['sin datos'];
                         sqlsrv_close($conn);
                         return $fechas;
                    }
               }

          }

          // Array para almacenar las fechas formateadas
          $fechas = array();

          // Procesar los resultados
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
               if ($row['FechaFormateada']."" == "1900-01-01") {
                    $fechas[] = "Todo";
               }else {
                    $fechas[] = $row['FechaFormateada'];
               }
          }

          // Cerrar la conexión
          sqlsrv_free_stmt($stmt);
          sqlsrv_close($conn);

          // Mostrar el array de fechas únicas
          return $fechas;
     }
     function obtenerProyecto($fecha, $tabla)
     {
          if ($tabla !== "CAJA") {
          if ($tabla == "DEPOSITOS") {
               $columnaFecha = "Fecha";
               $columnaSelect = "Caja";
          } else {
               $columnaFecha = "Fecha_compra";
               $columnaSelect = "Project_Desc";
          }
          if ($tabla == "REGISTROS_RCE" || $tabla == "PROPUESTA") {
               $proyectos = ["Sin datos"];
          } else {
               $conn = Conectar::conectar();
               // Consulta SQL para extraer y formatear la fecha
               $sql = "SELECT DISTINCT " . $columnaSelect . " AS Proyectos FROM $tabla WHERE FORMAT(CAST(" . $columnaFecha . " AS DATE), 'yyyy-MM') = '" . $fecha . "'";

               $stmt = sqlsrv_query($conn, $sql);

               // Verificar la ejecución de la consulta
               if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
               }

               // Array para almacenar las fechas formateadas
               $proyectos = array();

               // Procesar los resultados
               while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $proyectos[] = $row['Proyectos'];
               }

               // Cerrar la conexión
               sqlsrv_free_stmt($stmt);
               sqlsrv_close($conn);

               // Mostrar el array de fechas únicas
          }
          return $proyectos;
          }else {
               $proyectos = ['sin datos'];
               return $proyectos;
          }
          
     }

     function actualizarRegistro($columnas, $valores, $cod, $tabla)
     {
          $conn = Conectar::conectar();
          if (count($columnas) !== count($valores)) {
               die("El número de columnas y valores no coincide.");
          }

          // Construir la consulta SQL de actualización dinámicamente
          $sets = [];
          $params = [];

          foreach ($columnas as $index => $columna) {
               $sets[] = "$columna = ?";
          }
          $params = $valores;
          $sql = "UPDATE $tabla SET " . implode(', ', $sets) . " WHERE COD = $cod";
          //$params[] = $cod;

          // Preparar y ejecutar la consulta
          $stmt = sqlsrv_query($conn, $sql, $params);

          // Verificar si la actualización fue exitosa
          if ($stmt === false) {
               die(print_r(sqlsrv_errors(), true));
          } else {
               echo "Registro actualizado exitosamente.";
          }
          sqlsrv_free_stmt($stmt);
          sqlsrv_close($conn);
     }

}
?>