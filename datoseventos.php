<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para obtener los datos JSON enviados mediante POST
function obtenerDatosJson() {
    $contenido = file_get_contents('php://input');
    return json_decode($contenido, true);
}

header('Content-Type: application/json');

include 'conexion.php';

$conexion = regresarConexion();

switch ($_GET['accion']) {
    case 'listar':
        $datos = mysqli_query($conexion,"SELECT id,
        titulo AS title,
        descripcion,
        inicio AS start,
        fin AS end,
        colorfondo AS backgroundColor,
        colortexto AS textColor
        FROM eventos");

        $resultado = array();

        while ($row = mysqli_fetch_assoc($datos)) {
            if (!is_null($row['start'])) {
                $row['start'] = date('Y-m-d\TH:i:s', strtotime($row['start']));
            } else {
                unset($row['start']);
            }
        
            if (!is_null($row['end'])) {
                $row['end'] = date('Y-m-d\TH:i:s', strtotime($row['end']));
            } else {
                unset($row['end']);
            }
        
            $resultado[] = $row;
        }
        echo json_encode($resultado);

        break;
    case 'agregar':
        $respuesta = mysqli_query($conexion, "insert into eventos (titulo, descripcion, inicio, fin, colortexto, colorfondo) values 
            ('$_POST[titulo]','$_POST[descripcion]','$_POST[inicio]','$_POST[fin]','$_POST[colortexto]','$_POST[colorfondo]')");
        
        echo json_encode($respuesta);
        // try {
        //     $data = obtenerDatosJson(); // Obtener datos JSON como un array
        
        //     // Verificar que todos los campos necesarios estén presentes
        //     if (isset($data['titulo'], $data['descripcion'], $data['inicio'], $data['fin'], $data['colorFondo'], $data['colorTexto'])) {
        //         $consulta = "INSERT INTO eventos (titulo, descripcion, inicio, fin, colorfondo, colortexto) VALUES (?, ?, ?, ?, ?, ?)";
        //         $sentencia = $conexion->prepare($consulta);
        //         $sentencia->bind_param("ssssss", $data['titulo'], $data['descripcion'], $data['inicio'], $data['fin'], $data['colorFondo'], $data['colorTexto']);
        
        //         // Ejecutar la sentencia preparada
        //         if ($sentencia->execute()) {
        //             echo json_encode(array('respuesta' => 'Evento agregado con éxito'));
        //         } else {
        //             // Si hay un error en la ejecución, devuelve el mensaje de error.
        //             echo json_encode(array('respuesta' => 'Error al agregar evento', 'error' => $conexion->error));
        //         }
        //     } else {
        //         // Si alguno de los datos requeridos no está presente o es nulo, devuelve un error.
        //         echo json_encode(array('respuesta' => 'Datos incompletos o incorrectamente enviados'));
        //     }
        // } catch (Exception $e) {
        //     // Devolver un mensaje de error con el mensaje de la excepción
        //     echo json_encode(array('respuesta' => 'Error en el servidor', 'error' => $e->getMessage()));
        // }
        break;
    case 'modificar':
        $respuesta = mysqli_query($conexion, "update eventos set titulo = '$_POST[titulo]',
        descripcion = '$_POST[descripcion]', 
        inicio =  '$_POST[inicio]',
        fin = '$_POST[fin]',
        colortexto = '$_POST[colortexto]', 
        colorfondo = '$_POST[colorfondo]'
        where id = $_POST[id]");

        echo json_encode($respuesta);
        // $json = file_get_contents('php://input');
        // $data = json_decode($json, true);
    
        // if (isset($data['id'], $data['titulo'], $data['descripcion'], $data['inicio'], $data['fin'], $data['colorFondo'], $data['colorTexto'])) {
        //     $consulta = "UPDATE eventos SET titulo=?, descripcion=?, inicio=?, fin=?, colorfondo=?, colortexto=? WHERE id=?";
        //     $sentencia = $conexion->prepare($consulta);
        //     $sentencia->bind_param("ssssssi", $data['titulo'], $data['descripcion'], $data['inicio'], $data['fin'], $data['colorFondo'], $data['colorTexto'], $data['id']);
    
        //     if ($sentencia->execute()) {
        //         echo json_encode(array('respuesta' => 'Evento modificado con éxito'));
        //     } else {
        //         echo json_encode(array('respuesta' => 'Error al modificar evento', 'error' => $sentencia->error));
        //     }
        // } else {
        //     echo json_encode(array('respuesta' => 'Datos incompletos'));
        // }
        break;
    case 'borrar':
        $respuesta = mysqli_query($conexion, "delete from eventos where id = $_POST[id]");
       
        echo json_encode($respuesta);

        // $json = file_get_contents('php://input');
        // $data = json_decode($json, true);
    
        // if (isset($data['id'])) {
        //     $consulta = "DELETE FROM eventos WHERE id=?";
        //     $sentencia = $conexion->prepare($consulta);
        //     $sentencia->bind_param("i", $data['id']);
    
        //     if ($sentencia->execute()) {
        //         echo json_encode(array('respuesta' => 'Evento borrado con éxito'));
        //     } else {
        //         echo json_encode(array('respuesta' => 'Error al borrar evento', 'error' => $sentencia->error));
        //     }
        // } else {
        //     echo json_encode(array('respuesta' => 'ID del evento requerido'));
        // }
        break;
    default:
        echo "default";
        break;
}

?>