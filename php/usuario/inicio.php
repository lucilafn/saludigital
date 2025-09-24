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
<div class="container">
    <h1>Bienvenido</h1>

    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="form_iniciosesion.php"><img src="../../img/login.png"> Iniciar sesión</a>
        <a href="form_registro.php"><img src="../../img/register.png"> Registrarse</a>
    <?php else: ?>
        <a href="perfil.php"><img src="../../img/profile.png"> Mi Perfil</a>
        <a href="../turnos/pedirturno.php"><img src="../../img/calendar.png"> Turnos</a>
        <a href="cerrarsesion.php"><img src="../../img/logout.png"> Cerrar sesión</a>
    <?php endif; ?>
</div>
<?php endif; ?>

