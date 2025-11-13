<?php
require_once('../conexion.php');
session_start();

// Solo admin puede reservar
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Validar datos recibidos
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_horario']) || empty($_POST['id_usuario'])) {
    header("Location: administrador.php?error=datos");
    exit();
}

$id_horario = intval($_POST['id_horario']);
$id_usuario = intval($_POST['id_usuario']);

// Verificar si el horario sigue libre
$sql = "SELECT id_turno FROM turnos WHERE id_horario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_horario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Ya reservado
    header("Location: administrador.php?error=ocupado");
    exit();
}

// Insertar nuevo turno
$sql_insert = "INSERT INTO turnos (id_usuario, id_horario) VALUES (?, ?)";
$stmt = $conexion->prepare($sql_insert);
$stmt->bind_param("ii", $id_usuario, $id_horario);

if ($stmt->execute()) {
    header("Location: administrador.php?success=ok");
} else {
    header("Location: administrador.php?error=bd");
}
exit();
?>