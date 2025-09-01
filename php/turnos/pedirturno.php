<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    echo'<script>
    alert("Para poder ve su perfil debe iniciar sesión");
    window.location.href = "form_iniciarsesion.php";
    </script>';
}

$sql = "SELECT * FROM servicios AND profesionales AND turnos"; 
$resultado = mysqli_query($conexion, $sql);

while ($arreglo = mysqli_fetch_assoc($resultado)) {
    echo "<p>Nombre y apellido: " . htmlspecialchars($arreglo['nombre']) . htmlspecialchars($arreglo['apellido']) . "</p>";
    echo "<p>DNI: " . htmlspecialchars($arreglo['dni']) . "</p>";
    echo "<p>Teléfono: " . htmlspecialchars($arreglo['telefono']) . "</p>";
    echo "<p>Correo electrónico: " . htmlspecialchars($arreglo['email']) . "</p>";
    if (($arreglo['obrasocial'])){
    echo "<p>Obra social: " . htmlspecialchars($arreglo['obrasocial']) . "</p>"; 
    }else{
    echo "No posee";
    }
  }

  echo "<h1>Mis turnos pendientes: </h1>";
?>