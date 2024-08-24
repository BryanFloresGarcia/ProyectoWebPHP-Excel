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
               sqlsrv_free_stmt($crear);
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
                                   if (strpos($item, 'Numero_comprobante') !== false) {

                                   }
                                   break;
                         }
                         $j++;
                         $tsql = "ALTER TABLE " . $tabla . " ADD " . $item . " " . $tipoDato;
                         $insertar = sqlsrv_query($conn, $tsql);
                         if ($insertar == FALSE)
                              echo ("Error! Agregando: " . $item . "<br>");
                    }

               }
               //echo ("Error! Agregando: ".$tsql);
               sqlsrv_free_stmt($insertar);
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
     function escribirCampos($arrayColumna, $arrayDatos, $tabla)
     {
          $arrayValores = array();
          try {
               $conn = Conectar::conectar();
               $columnas = "";
               $valores = "";
               $Numero_comprobante = 0;
               $contador = 0;
               $i = 0;
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
                         $arrayValores[$i] = $campo;
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
               sqlsrv_free_stmt($stmt);
               sqlsrv_close($conn);


          } catch (Exception $e) {
               echo ("Error!");
          }
     }
     function obtenerRegistros($tabla, $n, $cod)
     {
          $conn = Conectar::conectar();

          // Consulta para obtener los últimos n registros
          if ($cod >= 1) {
               if ($cod == 1) {
                    $cod = 0;
               }
               $consulta = "SELECT  * FROM " . $tabla . " ORDER BY COD DESC OFFSET ".  $cod ." ROWS FETCH NEXT (?) ROWS ONLY;";
          }else{
               $consulta = "SELECT TOP (?) * FROM " . $tabla . " ORDER BY COD ASC;";
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
          $conn = Conectar::conectar();

          $consulta = "SELECT * FROM $tabla WHERE FORMAT(CAST(Fecha_compra AS DATE), 'yyyy-MM') = ?";
          // Preparar y ejecutar consulta
          $params = [$fecha];
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

     function obtenerTablas() {
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

     function validarTabla($tabla) {
          $conn = Conectar::conectar();
          $query = "SELECT TOP (1) * FROM " . $tabla;
          $stmt = sqlsrv_query($conn, $query);

          if ($stmt === false) {
               sqlsrv_close($conn);
               return false;
          }else {
               sqlsrv_free_stmt($stmt);
               sqlsrv_close($conn);
               return true;
          }
          
     }
     function obtenerColumnas($nombreTabla) {
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
     function totalDeRegistros(){
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
     function obtenerAnioyMes($tabla) {
          $conn = Conectar::conectar();
          // Consulta SQL para extraer y formatear la fecha
          $sql = "
          SELECT DISTINCT 
          FORMAT(CAST(Fecha_compra AS DATE), 'yyyy-MM') AS FechaFormateada
          FROM $tabla";

          // Ejecutar la consulta
          $stmt = sqlsrv_query($conn, $sql);

          // Verificar la ejecución de la consulta
          if ($stmt === false) {
          die(print_r(sqlsrv_errors(), true));
          }

          // Array para almacenar las fechas formateadas
          $fechas = array();

          // Procesar los resultados
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
          $fechas[] = $row['FechaFormateada'];
          }

          // Cerrar la conexión
          sqlsrv_free_stmt($stmt);
          sqlsrv_close($conn);

          // Mostrar el array de fechas únicas
          return $fechas;
     }
     function obtenerProyecto($fecha, $tabla) {
          $conn = Conectar::conectar();
          // Consulta SQL para extraer y formatear la fecha
          $sql = "SELECT DISTINCT Project_Desc AS Proyectos FROM $tabla WHERE FORMAT(CAST(Fecha_compra AS DATE), 'yyyy-MM') = '".$fecha."'";

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
          return $proyectos;
     }

     function actualizarRegistro($columnas, $valores, $cod, $tabla) {
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