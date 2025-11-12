<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Capturar datos
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$id_servicio = intval($_POST['id_servicio']);
$dias_trabajados = trim($_POST['dias_trabajados_texto']);
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$intervalo = intval($_POST['intervalo']);

// Insertar
$sql = "INSERT INTO profesionales (nombre, apellido, id_servicio, dias_trabajados, hora_inicio, hora_fin, intervalo)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssisssi", $nombre, $apellido, $id_servicio, $dias_trabajados, $hora_inicio, $hora_fin, $intervalo);

if ($stmt->execute()) {
    echo "<script>alert('Profesional registrado correctamente.');window.location='profesionales.php';</script>";
} else {
    echo "<script>alert('Error al registrar profesional.');window.location='profesionales.php';</script>";
}
?>
