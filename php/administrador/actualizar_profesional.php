<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$id = intval($_POST['id_profesional']);
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$id_servicio = intval($_POST['id_servicio']);
$dias = trim($_POST['dias_trabajados_texto']);
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$intervalo = intval($_POST['intervalo']);

$sql = "UPDATE profesionales
        SET nombre=?, apellido=?, id_servicio=?, dias_trabajados=?, hora_inicio=?, hora_fin=?, intervalo=?
        WHERE id_profesional=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssisssii", $nombre, $apellido, $id_servicio, $dias, $hora_inicio, $hora_fin, $intervalo, $id);

if ($stmt->execute()) {
    echo "<script>alert('Profesional actualizado correctamente.');window.location='profesionales.php';</script>";
} else {
    echo "<script>alert('Error al actualizar profesional.');window.location='profesionales.php';</script>";
}
?>
