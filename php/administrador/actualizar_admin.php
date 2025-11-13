<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_turno']) || empty($_POST['id_horario'])) {
    header("Location: administrador.php");
    exit();
}

$id_turno = intval($_POST['id_turno']);
$id_horario = intval($_POST['id_horario']);

// Verificar disponibilidad
$sql_check = "SELECT id_turno FROM turnos WHERE id_horario = ?";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("i", $id_horario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    header("Location: administrador.php?error=ocupado");
    exit();
}

// Actualizar turno
$sql_update = "UPDATE turnos SET id_horario = ? WHERE id_turno = ?";
$stmt = $conexion->prepare($sql_update);
$stmt->bind_param("ii", $id_horario, $id_turno);

if ($stmt->execute()) {
    header("Location: administrador.php?success=modificado");
} else {
    header("Location: administrador.php?error=bd");
}
exit();
?>