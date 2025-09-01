<?php
include('../conexion.php');
session_start();

$sql = "SELECT * FROM servicios AND profesionales"; 
$resultado = mysqli_query($conexion, $sql);
echo "<h1>Nuestras prestaciones: </h1>";

while ($arreglo = mysqli_fetch_assoc($resultado)) {
    echo "<p>" . htmlspecialchars($arreglo['servicio']) . "</p>";
    echo "<p>" . htmlspecialchars($arreglo['profesional']) . "</p>";
    echo "<p>" . htmlspecialchars($arreglo['descripcion']) . "</p>";
}
?>