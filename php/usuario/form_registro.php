<?php
include_once('../conexion.php');

session_start();

if (isset($_SESSION['usuario']))
{
echo'<script>
alert("No puede ingresar teniendo una sesion activa");
window.location.href = "inicio.php";
</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/form_registrar.css">
    <title>Registro</title>
</head>
<body>
<div>
    <form action="registro.php" method="POST">
        <center><h2>¡!</h2></center>
        <div class="contenedor-registrar">
            <center><h2>:</h2></center>
            <div class="cajitas">
            <input class="usuario" type="text" name="nombre" id="nombre" placeholder="Ingresá tu nombre" required autocomplete="off"><br>
            <input class="apellido" type="text" name="apellido" id="apellido" placeholder="Ingresá tu apellido" required autocomplete="off"><br>
            <input class="dni" type="text" name="dni" id="dni" placeholder="Ingresá tu dni" required autocomplete="off"><br>
            <input class="telefono" type="telefono" name="telefono" id="telefono" placeholder="Ingresá tu telefono" required autocomplete="off"><br>
            <p>En caso de no tener obra social, deje el casillero en blanco</p>
            <input class="obrasocial" type="obrasocial" name="obrasocial" id="obrasocial" placeholder="Ingresá tu obra social si es que posees"autocomplete="off"><br>
            <input class="email" type="email" name="email" id="email" placeholder="Ingresá tu correo" required autocomplete="off"><br>
            <input class="contrasenia" type="password" name="contrasenia" id="contrasenia" placeholder="Ingresá tu contraseña" required autocomplete="off"><br>
            <center>
                <input class="boton" type="submit" value="Registrar" name="registrar">
                <input class="boton" type="reset" value="Limpiar">
            </center></div>
            <center><p><a href="form_iniciosesion.php">¿Ya estas registrado? ¡Clickeá acá!</a></p>
            <p><a href="inicio.php">Volver al menú</a></p></center>
        </div>
    </form>
</div>
</body>
</html>
