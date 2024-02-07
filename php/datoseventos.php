<?php

session_start();

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
        usuario,
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
        $respuesta = mysqli_query($conexion, "insert into eventos (titulo, descripcion, inicio, fin, colortexto, colorfondo, usuario) values 
            ('$_POST[titulo]','$_POST[descripcion]','$_POST[inicio]','$_POST[fin]','$_POST[colortexto]','$_POST[colorfondo]', '$_SESSION[usuario]')");
        
        echo json_encode($respuesta);
        break;
    case 'modificar':
            // Obtener el ID del evento y el nombre de usuario del evento
            $id = $_POST['id'];
            $usuario = $_SESSION['usuario'];

            // Obtener el usuario que creó el evento
            $query = "SELECT usuario FROM eventos WHERE id = $id";
            $resultado = mysqli_query($conexion, $query);
            $fila = mysqli_fetch_assoc($resultado);

            // Verificar si el usuario del evento es el mismo que el usuario logueado
            if ($fila['usuario'] == $usuario) {
                // El usuario puede modificar el evento
                $respuesta = mysqli_query($conexion, "UPDATE eventos SET titulo = '$_POST[titulo]',
                descripcion = '$_POST[descripcion]', 
                inicio =  '$_POST[inicio]',
                fin = '$_POST[fin]',
                colortexto = '$_POST[colortexto]', 
                colorfondo = '$_POST[colorfondo]'
                WHERE id = $id");
                echo json_encode(array('success' => true));
            } else {
                // El usuario no tiene permisos para modificar el evento
                echo json_encode(array('success' => false, 'message' => 'No tiene permisos para modificar este evento'));
            }
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