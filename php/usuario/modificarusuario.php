<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/modificarusuario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar usuario</title>
</head>
<body>
    <a href='perfil.php'>Volver</a>
<?php
require_once('../conexion.php');
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: form_iniciosesion.php");
    exit();
}

// Trae los datos del usuario actual
$idusuario = $_SESSION['idusuario'];
$sql = "SELECT * FROM usuarios WHERE id_usuario = '$idusuario'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    ?>
    <h1>Modificar usuario</h1>
    <form action="actualizarusuario.php" method="POST" onsubmit="return validarCampos()">
        <input hidden name="idusuario" value="<?= $fila['id_usuario'] ?>" type="number">

        <p>Nombre: <input name="nombre" value="<?= htmlspecialchars($fila['nombre']) ?>" type="text" required></p>
        <p>Apellido: <input name="apellido" value="<?= htmlspecialchars($fila['apellido']) ?>" type="text" required></p>
        <p>DNI: <input name="dni" value="<?= htmlspecialchars($fila['dni']) ?>" type="text" required></p>
        <p>Teléfono: <input name="telefono" value="<?= htmlspecialchars($fila['telefono']) ?>" type="tel" required></p>
        <p>Obra social: <input name="obra_social" value="<?= htmlspecialchars($fila['obra_social']) ?>" type="text"></p>
        <p>Correo: <input name="email" value="<?= htmlspecialchars($fila['email']) ?>" type="email" required></p>

        <hr>
        <h3>Cambiar contraseña (opcional)</h3>
        <p>Contraseña actual: <input name="contrasenia" type="password"></p>
        <p>Nueva contraseña: <input name="contra_nueva" type="password"></p>
        <p><small>Deje ambos campos vacíos si no desea cambiar su contraseña.</small></p>

        <br>
        <input type="submit" value="Actualizar">
    </form>

    <script>
    function validarCampos() {
        const nombre = document.querySelector('[name="nombre"]').value.trim();
        const apellido = document.querySelector('[name="apellido"]').value.trim();
        const dni = document.querySelector('[name="dni"]').value.trim();
        const telefono = document.querySelector('[name="telefono"]').value.trim();
        const correo = document.querySelector('[name="email"]').value.trim();
        const contrasenia = document.querySelector('[name="contrasenia"]').value.trim();
        const contraNueva = document.querySelector('[name="contra_nueva"]').value.trim();

        // Validar campos vacíos
        if (!nombre || !apellido || !dni || !telefono || !correo) {
            alert("Por favor complete todos los campos obligatorios.");
            return false;
        }

        // Validación de contraseñas
        if (contrasenia && !contraNueva) {
            alert("Si desea cambiar su contraseña debe ingresar una nueva contraseña.");
            return false;
        }
        return true;
    }
    </script>
<?php
} else {
    echo "<p>No se encontró el usuario.</p>";
}
?>
</body>
</html>