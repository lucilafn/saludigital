<?php
include('../conexion.php');
session_start();

// ------------------- VERIFICACIÓN DE SESIÓN -------------------
if (!isset($_SESSION['idusuario'])) {
    header("Location: ../usuario/form_iniciosesion.php");
    exit();
}

$id_usuario = intval($_SESSION['idusuario']); // ID del usuario logueado

// ------------------- VERIFICACIÓN DE DATOS POST -------------------
if (!isset($_POST['id_horario'])) {
    header("Location: pedirturno.php?error=invalid");
    exit();
}

$id_horario = intval($_POST['id_horario']);

// Opcional: mantener filtros al volver
$filtro_servicio = isset($_POST['filtro_servicio']) ? intval($_POST['filtro_servicio']) : 0;
$filtro_profesional = isset($_POST['filtro_profesional']) ? intval($_POST['filtro_profesional']) : 0;

// ------------------- VERIFICAR QUE EL TURNO NO ESTÉ RESERVADO -------------------
$sql_check = "SELECT id_turno FROM turnos WHERE id_horario = $id_horario";
$res_check = mysqli_query($conexion, $sql_check);

if (!$res_check) {
    header("Location: pedirturno.php?error=db");
    exit();
}

if (mysqli_num_rows($res_check) > 0) {
    // Turno ya reservado
    $url = "pedirturno.php?error=ocupado";
    if ($filtro_servicio) $url .= "&servicio=$filtro_servicio";
    if ($filtro_profesional) $url .= "&profesional=$filtro_profesional";
    header("Location: $url");
    exit();
}

// ------------------- INSERTAR TURNO -------------------
$sql_insert = "INSERT INTO turnos (id_horario, id_usuario) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $sql_insert);
if (!$stmt) {
    header("Location: pedirturno.php?error=db");
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $id_horario, $id_usuario);

if (mysqli_stmt_execute($stmt)) {
    // Reserva exitosa
    $url = "pedirturno.php?success=1";
    if ($filtro_servicio) $url .= "&servicio=$filtro_servicio";
    if ($filtro_profesional) $url .= "&profesional=$filtro_profesional";
    header("Location: $url");
    exit();
} else {
    // Error al insertar
    $url = "pedirturno.php?error=db";
    if ($filtro_servicio) $url .= "&servicio=$filtro_servicio";
    if ($filtro_profesional) $url .= "&profesional=$filtro_profesional";
    header("Location: $url");
    exit();
}