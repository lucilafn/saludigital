<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Recuperar datos y limpiar
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$dni = trim($_POST['dni']);
$telefono = trim($_POST['telefono']);
$obra_social = trim($_POST['obra_social']);
$email = trim($_POST['email']);
$contrasenia = trim($_POST['contrasenia']);

// Verificar campos vacíos
if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono) || empty($email) || empty($contrasenia)) {
    echo "<script>alert('Por favor complete todos los campos obligatorios.');window.location='pacientes.php';</script>";
    exit();
}

// Verificar si el DNI o email ya existen
$check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE (dni = ? OR email = ?) AND rol = 0");
$check->bind_param("ss", $dni, $email);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo "<script>alert('El DNI o correo ya están registrados.');window.location='pacientes.php';</script>";
    exit();
}

// Encriptar contraseña
$contrasenia_md5 = md5($contrasenia);

// Insertar paciente
$sql = "INSERT INTO usuarios (nombre, apellido, dni, telefono, obra_social, email, contrasenia, rol)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssss", $nombre, $apellido, $dni, $telefono, $obra_social, $email, $contrasenia_md5);

if ($stmt->execute()) {
    echo "<script>alert('Paciente registrado correctamente.');window.location='pacientes.php';</script>";
} else {
    echo "<script>alert('Error al registrar paciente.');window.location='pacientes.php';</script>";
}

$stmt->close();
$conexion->close();
?>