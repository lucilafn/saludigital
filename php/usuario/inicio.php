<?php
  include_once('../conexion.php');
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
  </head>
  <body>

    <!-- Si no hay una sesion existente-->
    <?php if (!isset($_SESSION['usuario'])):?>
    <a href="form_iniciosesion.php">Iniciar sesión</a>
    <a href="form_registro.php">Registrarse</a>
    <?php endif; ?>
    
    <!-- Si hay una sesion existente-->
    <?php if (isset($_SESSION['usuario'])): ?>
    <a href="perfil.php">Mi Perfil</a>
    <a href="../turnos/turnos.php">Turnos</a>
    <?php endif;?>
      

  </body>
</html>