<?php
// Conexión a la base de datos
include('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: ../usuario/form_iniciosesion.php");
    exit();
}
$id_usuario = ($_SESSION['idusuario']); 

// Si no viene el id del turno por GET, lo mandamos de nuevo al perfil
if (!isset($_GET['id_turno'])) {
    header("Location: ../perfil.php");
    exit();
}
$id_turno = ($_GET['id_turno']); 

// Buscamos el turno en la BD para validar que pertenece al usuario logueado
$sql = "SELECT t.id_turno, h.id_horario, p.id_profesional, s.id_servicio
        FROM turnos t
        INNER JOIN horarios h ON t.id_horario = h.id_horario
        INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
        INNER JOIN servicios s ON p.id_servicio = s.id_servicio
        WHERE t.id_turno = $id_turno AND t.id_usuario = $id_usuario";

$res = mysqli_query($conexion, $sql);

// Si no encuentra turno, mostramos error
if (mysqli_num_rows($res) == 0) {
    die("Turno no encontrado.");
}

$turno = mysqli_fetch_assoc($res); // Guarda los datos del turno

// Busca horarios del mismo servicio que estén libres (sin turno asignado) y que sean futuros (fecha mayor a hoy, o si es hoy que la hora sea mayor a la actual)
$sql_h = "SELECT h.id_horario, h.dia, h.hora, 
          p.nombre AS nombre_prof, p.apellido AS apellido_prof
          FROM horarios h
          INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
          LEFT JOIN turnos t ON h.id_horario = t.id_horario
          WHERE p.id_servicio = {$turno['id_servicio']}
          AND (h.dia > CURDATE() OR (h.dia = CURDATE() AND h.hora > CURTIME()))
          AND t.id_turno IS NULL
          ORDER BY h.dia, h.hora";

$res_h = mysqli_query($conexion, $sql_h);

?>
<h1>Modificar turno</h1>

<form method="post" action="actualizarturno.php">
    <!-- Se envía el id_turno que el usuario quiere modificar -->
    <input type="hidden" name="id_turno" value="<?= $id_turno ?>">

    <label>Nuevo horario:</label><br>

    <!-- Select con todos los horarios disponibles -->
    <select name="id_horario" required>
        <?php while ($fila = mysqli_fetch_assoc($res_h)): ?>
            <option value="<?= $fila['id_horario'] ?>">
                <!-- Mostramos la fecha, hora y nombre del profesional -->
                <?= htmlspecialchars($fila['dia'].' '.$fila['hora'].' - '.$fila['nombre_prof'].' '.$fila['apellido_prof']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <br><br>
    <button type="submit">Confirmar cambio</button>
</form>