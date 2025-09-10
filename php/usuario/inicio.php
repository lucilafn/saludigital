<?php
include('../conexion.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- si no existe el usuario aparecen los botones, si existe le da la bienvenida y desaparecen los botones -->
    <?php if (!isset($_SESSION['usuario'])): ?>
    <a href="form_iniciosesion.php">Iniciar sesión</a>
    <a href="form_registro.php">Registrarse</a>
  <?php endif; ?>
  <?php if (isset($_SESSION['usuario'])): ?>
    <a href="cerrarsesion.php">Cerrar Sesion</a>
    <a href="perfil.php">Mi Perfil</a>
    <a href="../turnos/turnos.php">Turnos</a>
  <?php endif; ?>
</body>
</html>

