<?php
  // Incluir el archivo de conexión a la base de datos
  require_once('../conexion.php');

  // Inicia la sesión
  session_start();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <title>Inicio</title>
  </head>
  <body>

    <!-- Si no hay una sesion existente... -->
    <?php
      if (!isset($_SESSION['usuario'])):
    ?>

    <!-- Mostrar enlaces de inicio de sesión y registro -->
    <a href="form_iniciosesion.php">Iniciar sesión</a>
    <a href="form_registro.php">Registrarse</a>

    <?php
      endif;
    ?>

    <!-- Si hay una sesion existente... -->
    <?php
      if (isset($_SESSION['usuario'])):
    ?>

    <!-- Mostrar enlaces de perfil, turnos y cerrar sesion -->
    <a href="perfil.php">Mi Perfil</a>
    <a href="../turnos/pedirturno.php">Turnos</a>
    <a href="cerrarsesion.php">Cerrar sesión</a>

    <?php
      endif;
    ?>

  </body>
</html>