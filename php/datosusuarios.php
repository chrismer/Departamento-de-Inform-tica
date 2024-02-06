<?php

header('Content-Type: application/json');

require 'conexion.php';

$conexion = regresarConexion();

switch ($_GET['accion']) {
    case 'agregar':
        $usuario = mysqli_real_escape_string($conexion, $_POST['nombrenuevo']);
        $password = mysqli_real_escape_string($conexion, $_POST['clave1']);
        $respuesta = mysqli_query($conexion, "insert into usuario (usuario,password) values ('$usuario','$password')");

        echo json_encode($respuesta);

        break;
    case 'existe':
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombrenuevo']);
        $respuesta = mysqli_query($conexion, "select usuario from usuario where usuario='$nombre'");

        $cantidad = mysqli_num_rows($respuesta);
        if($cantidad == 1){
            echo '{"resultado": "repetido"}';
        }else{
            echo '{"resultado": "norepetido"}';
        }

        break;
    
    default:
        echo 'hubo un error';
        break;
}

?>