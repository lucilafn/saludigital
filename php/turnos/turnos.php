<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    echo '<script>
        alert("Para ingresar debe tener una sesión iniciada");
        window.location.href = "../usuario/form_iniciosesion.php";
    </script>';
    exit(); // Importante para detener la ejecución
}

// Consulta SQL con los IDs necesarios
$sql = "SELECT s.id_servicio, s.nombre AS servicio, s.descripcion,
        p.id_profesional, p.nombre AS nombre, p.apellido
        FROM servicios s
        INNER JOIN profesionales p 
        ON s.id_servicio = p.id_servicio";

$resultado = mysqli_query($conexion, $sql);

// Verificamos si hubo error en la consulta
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Guardamos todos los resultados en un array
$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila;
}
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
            // Usamos array_column para evitar opciones repetidas
            $serviciosMostrados = [];
            foreach ($datos as $fila) {
                if (!in_array($fila['id_servicio'], $serviciosMostrados)) {
                    echo "<option value='" . $fila['id_servicio'] . "'>" .
                    htmlspecialchars($fila['servicio']) . "</option>";
                    $serviciosMostrados[] = $fila['id_servicio'];
                }
            }
            ?>
        </select>
        <br><br>

        <label for="profesional">¿Con qué profesional prefieres atenderte?</label><br>
        <select name="profesional" id="profesional" required>
            <option value="">-- Selecciona --</option>
            <?php
            // Evitamos duplicados si un profesional ofrece varios servicios
            $profesionalesMostrados = [];
            foreach ($datos as $fila) {
                if (!in_array($fila['id_profesional'], $profesionalesMostrados)) {
                    $nombreCompleto = $fila['nombre'] . ' ' . $fila['apellido'];
                    echo "<option value='" . $fila['id_profesional'] . "'>" .
                         htmlspecialchars($nombreCompleto) . "</option>";
                    $profesionalesMostrados[] = $fila['id_profesional'];
                }
            }
            ?>
        </select>
        <br><br>

        <button type="submit">Buscar turnos disponibles</button>
    </form>
</body>
</html>