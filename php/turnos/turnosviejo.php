<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    echo'<script>
    alert("Para ingresar debe tener una sesion iniciada");
    window.location.href = "../usuario/form_iniciosesion.php";
    </script>';
}

$sql = "SELECT s.nombre AS servicio, 
        s.descripcion, 
        p.nombre AS nombre, 
        p.apellido
        FROM servicios s
        INNER JOIN profesionales p 
        ON s.id_servicio = p.id_servicio";
$resultado = mysqli_query($conexion, $sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedir Turno</title>
</head>
<body>
    <h1>Pedí tu turno</h1>

    <form action="pedirturno.php" method="POST">
        <label for="servicio">¿Qué servicio requieres?</label><br>
        <select name="servicio" id="servicio" required>
            <option value="">-- Selecciona --</option>
            <?php
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "<option value='".$fila['id_servicio']."'>".
                htmlspecialchars($fila['servicio'])."</option>";
            }
            ?>
        </select>
    </form>
    <br>
    <form action="pedirturno.php" method="POST">
        <label for="profesional">¿Con que profesional prefieres atenderte?</label><br>
        <select name="profesional" id="profesional">
            <option value="">-- Selecciona --</option>
            <?php
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "<option value='".$fila['id_profesional']."'>".
                htmlspecialchars($fila['profesional'])."</option>";
            }
            ?>
        </select>
    </form>
        <br><br>
        <button type="submit">Buscar turnos disponibles</button>
    
      
</body>
</html>