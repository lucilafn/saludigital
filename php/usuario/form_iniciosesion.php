<?php
  include_once('../conexion.php');
  session_start();

  if (isset($_SESSION['usuario']))
  {
    header("Location: inicio.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/form_iniciosesion.css">
    <title>Iniciar sesión</title>
  </head>
  <body class="">
    <div class = "">
      <div class="">
        <form action="iniciosesion.php" method="POST">
          <h2>Iniciar sesión</h2>
          <input class="email" type="text" name="email" id="email" placeholder="Ingresá tu email" required autocomplete="off"><br>
          <input class="contrasenia" type="password" name="contrasenia" id="contrasenia" placeholder="Ingresá tu contraseña" required autocomplete="off"><br>
          <center>
            <input class="boton" type="submit" name="iniciarsesion" value="Iniciar Sesión">
            <input class="boton" type="reset" value="Limpiar">
          </center><br>
        </form>
        <center>
          <p><a href="form_registro.php">¿No tenés una cuenta? ¡Clickeá acá!</a></p>
          <p><a href="inicio.php">Volver al menú</a></p>
        </center>
      </div>
    </div>
  </body>
</html>