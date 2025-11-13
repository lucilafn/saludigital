<?php
require_once('../conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Verifica sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: form_iniciosesion.php");
    exit();
}

$id = $_SESSION['idusuario'];

// Sanitizar entradas
$nombre = trim(mysqli_real_escape_string($conexion, $_POST['nombre']));
$apellido = trim(mysqli_real_escape_string($conexion, $_POST['apellido']));
$dni = trim(mysqli_real_escape_string($conexion, $_POST['dni']));
$telefono = trim(mysqli_real_escape_string($conexion, $_POST['telefono']));
$obra_social = trim(mysqli_real_escape_string($conexion, $_POST['obra_social']));
$email = trim(mysqli_real_escape_string($conexion, $_POST['email']));
$contrasenia = trim($_POST['contrasenia']);
$contra_nueva = trim($_POST['contra_nueva']);

// Validar campos obligatorios
if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono) || empty($email)) {
    echo "<p style='color:red;'>Debe completar todos los campos obligatorios.</p>";
    exit();
}

// Si se desea cambiar la contraseña
if (!empty($contrasenia) && !empty($contra_nueva)) {
    // Verificar contraseña actual
    $sql_verificar = "SELECT contrasenia FROM usuarios WHERE id_usuario = '$id'";
    $res_verificar = mysqli_query($conexion, $sql_verificar);
    $fila = mysqli_fetch_assoc($res_verificar);

    if ($fila['contrasenia'] !== md5($contrasenia)) {
        echo "<p style='color:red;'>La contraseña actual no es correcta.</p>";
        echo "<a href='modificarusuario.php'>Volver</a>";
        exit();
    }

    // Encriptar nueva contraseña
    $contra_encriptada = md5($contra_nueva);

    $sql_update = "UPDATE usuarios SET 
                    nombre='$nombre',
                    apellido='$apellido',
                    dni='$dni',
                    telefono='$telefono',
                    obra_social='$obra_social',
                    email='$email',
                    contrasenia='$contra_encriptada'
                   WHERE id_usuario='$id'";
} 
// Si no se cambia la contraseña
else if (empty($contrasenia) && empty($contra_nueva)) {
    $sql_update = "UPDATE usuarios SET 
                    nombre='$nombre',
                    apellido='$apellido',
                    dni='$dni',
                    telefono='$telefono',
                    obra_social='$obra_social',
                    email='$email'
                   WHERE id_usuario='$id'";
} 
// Si solo uno de los campos de contraseña está lleno
else {
    echo "<p style='color:red;'>Debe completar ambos campos de contraseña o dejarlos vacíos.</p>";
    echo "<a href='modificarusuario.php'>Volver</a>";
    exit();
}

// Ejecutar actualización
if (mysqli_query($conexion, $sql_update)) {
    header("Location: perfil.php");
    exit();
} else {
    echo "<p style='color:red;'>Error al actualizar el usuario: " . mysqli_error($conexion) . "</p>";
}
?>