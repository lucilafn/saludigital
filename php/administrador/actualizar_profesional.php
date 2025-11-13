<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ==================== FUNCIONES ====================
function normalizarDias($texto) {
    $texto = strtolower($texto);
    $buscar  = ['á', 'é', 'í', 'ó', 'ú'];
    $reempl = ['a', 'e', 'i', 'o', 'u'];
    return str_replace($buscar, $reempl, $texto);
}

// ==================== CAPTURA DE DATOS ====================
$id_profesional = intval($_POST['id_profesional']);
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$id_servicio = intval($_POST['id_servicio']);
$dias_trabajados = normalizarDias(trim($_POST['dias_trabajados_texto']));
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$intervalo = intval($_POST['intervalo']);

// ==================== ACTUALIZACIÓN ====================
$sql = "UPDATE profesionales 
        SET nombre=?, apellido=?, id_servicio=?, dias_trabajados=?, hora_inicio=?, hora_fin=?, intervalo=?
        WHERE id_profesional=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssisssii", $nombre, $apellido, $id_servicio, $dias_trabajados, $hora_inicio, $hora_fin, $intervalo, $id_profesional);

if ($stmt->execute()) {
    echo "<script>alert('Datos actualizados correctamente.');window.location='profesionales.php';</script>";
} else {
    echo "<script>alert('Error al actualizar profesional.');window.location='profesionales.php';</script>";
}
?>
