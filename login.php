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




// $servername = "localhost";
// $username = "tu_nombre_de_usuario";
// $password = "tu_contrasena";
// $dbname = "nombre_de_tu_base_de_datos";

// // Crear conexión
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Verificar conexión
// if ($conn->connect_error) {
//     die("Conexión fallida: " . $conn->connect_error);
// }

// // Limpieza de datos de entrada
// $usuario = $conn->real_escape_string($_POST['inputEmail']);
// $contrasena = $conn->real_escape_string($_POST['inputPassword']);

// // Consulta a la base de datos para verificar el usuario y la contraseña
// $sql = "SELECT * FROM usuarios WHERE email = '$usuario'";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     // Suponiendo que la contraseña está hasheada en la base de datos
//     $row = $result->fetch_assoc();
//     if (password_verify($contrasena, $row['contrasena'])) {
//         // Iniciar sesión y redirigir al usuario a una página segura
//         session_start();
//         $_SESSION['usuario'] = $usuario;
//         header("Location: pagina_segura.php");
//     } else {
//         // Contraseña incorrecta
//         header("Location: login.html?error=contrasena_incorrecta");
//     }
// } else {
//     // Usuario no encontrado
//     header("Location: login.html?error=usuario_no_encontrado");
// }

// $conn->close();
?>
