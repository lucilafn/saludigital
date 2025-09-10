<?php
  // include_once: incluye la conexion a la base de datos una unica vez en el archivo
  // session_start: permite usar $_SESSION (guarda datos de la sesion, como si esta registrado o no)
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

    <!-- Si no hay una sesion existente... -->
    <?php
      if (!isset($_SESSION['usuario'])):
    ?>

    <!-- Aparecen estos botones -->
    <a href="form_iniciosesion.php">Iniciar sesión</a>
    <a href="form_registro.php">Registrarse</a>
    
    <?php
      endif;
    ?>

    <!-- Y si hay... -->
    <?php
      if (isset($_SESSION['usuario'])):
    ?>

    <!-- Aparecen estos otros -->
    <a href="cerrarsesion.php">Cerrar Sesion</a>
    <a href="perfil.php">Mi Perfil</a>
    <a href="../turnos/turnos.php">Turnos</a>
    
    <?php
      endif;
    ?>

  </body>
</html>