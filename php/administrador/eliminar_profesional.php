<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$id = intval($_POST['id']);
$conexion->query("DELETE FROM profesionales WHERE id_profesional = $id");
echo "<script>alert('Profesional eliminado.');window.location='profesionales.php';</script>";
?>