<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Recuperar datos
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$dni = trim($_POST['dni']);
$telefono = trim($_POST['telefono']);
$obra_social = trim($_POST['obra_social']);
$correo = trim($_POST['email']);
$contrasenia = trim($_POST['clave']);

// Verificar si el DNI o email ya existen
$check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE dni = ? OR email = ?");
$check->bind_param("ss", $dni, $correo);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo "<script>alert('El DNI o correo ya están registrados.');window.location='pacientes.php';</script>";
    exit();
}

// Insertar paciente
$sql = "INSERT INTO usuarios (nombre, apellido, dni, telefono, obra_social, email, contrasenia, rol)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssss", $nombre, $apellido, $dni, $telefono, $obra_social, $correo, $contrasenia);

if ($stmt->execute()) {
    echo "<script>alert('Paciente registrado correctamente.');window.location='pacientes.php';</script>";
} else {
    echo "<script>alert('Error al registrar paciente.');window.location='pacientes.php';</script>";
}
?>