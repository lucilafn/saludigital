<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: ../usuario/form_iniciosesion.php");
    exit();
}
$id_usuario = intval($_SESSION['idusuario']);

if (!isset($_GET['id_turno'])) {
    header("Location: mis_turnos.php");
    exit();
}
$id_turno = intval($_GET['id_turno']);

// Traemos info del turno
$sql = "SELECT t.id_turno, h.id_horario, p.id_profesional, s.id_servicio
        FROM turnos t
        INNER JOIN horarios h ON t.id_horario = h.id_horario
        INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
        INNER JOIN servicios s ON p.id_servicio = s.id_servicio
        WHERE t.id_turno = $id_turno AND t.id_usuario = $id_usuario";
$res = mysqli_query($conexion, $sql);
if (mysqli_num_rows($res) == 0) {
    die("Turno no encontrado.");
}
$turno = mysqli_fetch_assoc($res);

// Buscar horarios libres del mismo servicio
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
<form method="post" action="procesar_modificacion.php">
    <input type="hidden" name="id_turno" value="<?= $id_turno ?>">
    <label>Nuevo horario:</label><br>
    <select name="id_horario" required>
        <?php while ($fila = mysqli_fetch_assoc($res_h)): ?>
            <option value="<?= $fila['id_horario'] ?>">
                <?= htmlspecialchars($fila['dia'].' '.$fila['hora'].' - '.$fila['nombre_prof'].' '.$fila['apellido_prof']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>
    <button type="submit">Confirmar cambio</button>
</form>
