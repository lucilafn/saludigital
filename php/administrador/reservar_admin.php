<?php
require_once('../conexion.php');
session_start();

// Verifica admin
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$id_usuario = intval($_POST['id_usuario']);
$id_horario = intval($_POST['id_horario']);

// Verifica si el turno ya fue tomado
$sql_check = "SELECT id_turno FROM turnos WHERE id_horario = $id_horario";
$res_check = mysqli_query($conexion, $sql_check);

if (mysqli_num_rows($res_check) > 0) {
    echo "<script>alert('El turno ya fue reservado por otro paciente.');window.location='administrador.php';</script>";
    exit();
}

// Inserta el turno
$sql_insert = "INSERT INTO turnos (id_horario, id_usuario) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $sql_insert);
mysqli_stmt_bind_param($stmt, "ii", $id_horario, $id_usuario);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Turno agendado correctamente.');window.location='administrador.php';</script>";
} else {
    echo "<script>alert('Error al agendar el turno.');window.location='administrador.php';</script>";
}
?>