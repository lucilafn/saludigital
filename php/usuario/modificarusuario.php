<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/modificarusuario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    require ('../conexion.php');
    session_start();

    if (!isset($_SESSION['usuario']))
    {
        header("Location: form_iniciosesion.php");
        exit();
    }

    $sql = "SELECT * FROM usuarios WHERE id_usuario = '".$_SESSION['idusuario']."'";
    $resultado = mysqli_query($conexion, $sql);

    if(mysqli_num_rows($resultado) > 0)
    {
        while ($fila = mysqli_fetch_assoc ($resultado))
        {
            echo "<h1>Modificar usuario</h1>";
            echo "<form action = 'actualizarusuario.php' method = 'POST'>";
            echo "<input hidden name = 'idusuario' value = '".$fila['id_usuario']."' type = 'number'>";
            echo "<p>Nombre: <input name = 'nombre' value = '".$fila['nombre']."' type = 'text'></p>";
            echo "<p>Apellido: <input name = 'apellido' value = '".$fila['apellido']."' type = 'text'></p>";
            echo "<p>DNI: <input name = 'dni' value = '".$fila['dni']."' type = 'text'></p>";
            echo "<p>Telefono: <input name = 'telefono' value = '".$fila['telefono']."' type = 'tel'></p>";
            echo "<p>Obra social: <input name = 'obra_social' value = '".$fila['obra_social']."' type = 'text'></p>";
            echo "<p>Correo: <input name = 'email' value = '".$fila['email']."' type = 'text'></p>";
            echo "<input type = 'submit' value = 'actualizar'>";
            echo "</form>";
        }
    }
?>
</body>
</html>