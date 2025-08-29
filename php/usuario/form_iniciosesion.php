 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">
    <title>Iniciar sesión</title>
</head>
<body class="">
<div class = "">
  <div class="">
    <form action="iniciosesion.php" method="POST">
        <h2>¡!</h2>
        <input class="email" type="text" name="email" id="email" placeholder="Ingresá tu email" required autocomplete="off">
        <br> 
        <input class="contrasenia" type="password" name="contrasenia" id="contrasenia" placeholder="Ingresá tu contraseña" required autocomplete="off">
        <br><br>
        <center><input class="boton" type="submit" name="iniciarsesion" value="Iniciar Sesión">
        <input class="boton" type="reset" value="Limpiar"></center>
        <br>
    </form>
    <center><p><a href="form_registro.php">¿No tenés una cuenta? ¡Clickeá acá!</a></p>
    <p><a href="/saludigital/index.php">Volver al menú</a></p></center>
  </div>
</div>
</body>
</html>