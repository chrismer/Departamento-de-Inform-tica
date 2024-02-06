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
        break;
    case 'borrar':
        $respuesta = mysqli_query($conexion, "delete from eventos where id = $_POST[id]");

        echo json_encode($respuesta);
        break;
    default:
        echo "default";
        break;
}

?>