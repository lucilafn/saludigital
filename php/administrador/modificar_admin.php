<?php
require_once('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: administrador.php");
    exit();
}

$id_turno = intval($_GET['id']);

// Obtener datos del turno actual
$sql_turno = "SELECT t.id_turno, t.id_usuario, h.id_horario, h.dia, h.hora,
                     p.id_profesional, p.nombre AS nombre_prof, p.apellido AS apellido_prof,
                     s.id_servicio, s.nombre AS nombre_servicio
              FROM turnos t
              INNER JOIN horarios h ON t.id_horario = h.id_horario
              INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
              INNER JOIN servicios s ON p.id_servicio = s.id_servicio
              WHERE t.id_turno = ?";
$stmt = $conexion->prepare($sql_turno);
$stmt->bind_param("i", $id_turno);
$stmt->execute();
$turno = $stmt->get_result()->fetch_assoc();

if (!$turno) {
    header("Location: administrador.php");
    exit();
}

// Buscar horarios disponibles para ese mismo profesional y servicio
$sql_horarios = "SELECT h.id_horario, h.dia, h.hora
                 FROM horarios h
                 LEFT JOIN turnos t ON h.id_horario = t.id_horario
                 WHERE t.id_turno IS NULL
                   AND (h.dia > CURDATE() OR (h.dia = CURDATE() AND h.hora > CURTIME()))
                   AND h.id_profesional = ?
                 ORDER BY h.dia, h.hora";
$stmt2 = $conexion->prepare($sql_horarios);
$stmt2->bind_param("i", $turno['id_profesional']);
$stmt2->execute();
$res_horarios = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar turno</title>
</head>
<body>
<a href="administrador.php">Volver</a>
<h1>Modificar turno</h1>

<p><strong>Profesional:</strong> <?= htmlspecialchars($turno['apellido_prof'].' '.$turno['nombre_prof']) ?></p>
<p><strong>Servicio:</strong> <?= htmlspecialchars($turno['nombre_servicio']) ?></p>
<p><strong>Turno actual:</strong> <?= htmlspecialchars($turno['dia'].' '.$turno['hora']) ?></p>

<form method="post" action="actualizar_admin.php">
    <input type="hidden" name="id_turno" value="<?= $turno['id_turno'] ?>">
    <label>Nuevo horario:</label><br>
    <select name="id_horario" required>
        <?php while ($h = $res_horarios->fetch_assoc()): ?>
            <option value="<?= $h['id_horario'] ?>">
                <?= htmlspecialchars($h['dia'].' '.$h['hora']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>
    <button type="submit">Actualizar</button>
</form>

</body>
</html>