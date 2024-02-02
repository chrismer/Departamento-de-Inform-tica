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

require 'conexion.php';

$conexion = regresarConexion();

switch ($_GET['accion']) {
    case 'listar':
        $datos = mysqli_query($conexion, "SELECT id,titulo,horainicio,horafin,colortexto,colorfondo FROM eventospredefinidos");
        $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
        echo json_encode($resultado);
        break;
    case 'agregar':
        $respuesta = mysqli_query($conexion, "insert into eventospredefinidos (titulo,horainicio,horafin,colortexto,colorfondo) values 
                                                ('$_POST[titulo]', '$_POST[horainicio]', '$_POST[horafin]', '$_POST[colortexto]', '$_POST[colorfondo]')");
        echo json_encode($respuesta);
        break;
    case 'borrar':
        // if(isset($_POST['id']) && is_numeric($_POST['id'])) {
        //     $id = $_POST['id'];
        //     $stmt = $conexion->prepare("DELETE FROM eventospredefinidos WHERE id = ?");
        //     $stmt->bind_param("i", $id);
        //     $respuesta = $stmt->execute();
        //     $stmt->close();
        //     echo json_encode($respuesta);
        //   } else {
        //     echo json_encode(false);
        //   }
        $respuesta = mysqli_query($conexion, "delete from eventospredefinidos where id=$_POST[id]");
        echo json_encode($respuesta);
        break;
    default:
        // echo 'nada por aqui';
        break;
}

?>