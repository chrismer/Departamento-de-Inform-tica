<?php

require('conexion.php');

$conexion = regresarConexion();

$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
$password = mysqli_real_escape_string($conexion, $_POST['password']);

$respuesta = mysqli_query($conexion, "select usuario, password from usuario where usuario='$usuario' and password='$password'");

$cantidad = mysqli_num_rows($respuesta);

if($cantidad == 1){
    session_start();
    $_SESSION['usuario'] = $usuario;
    echo 'Correcto';
}else{
    echo 'incorrecto';
}

?>
