<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICACIÓN DE SESIÓN -------------------
if (!isset($_SESSION['idusuario'])) {
    header("Location: ../usuario/form_iniciosesion.php");
    exit();
}
$id_usuario = intval($_SESSION['idusuario']);

// ------------------- TRAER SERVICIOS Y PROFESIONALES -------------------
$sql = "SELECT s.id_servicio, s.nombre AS servicio, 
               p.id_profesional, p.nombre AS nombre_prof, p.apellido AS apellido_prof
        FROM servicios s
        LEFT JOIN profesionales p ON s.id_servicio = p.id_servicio
        ORDER BY s.nombre, p.nombre, p.apellido";
$resultado = mysqli_query($conexion, $sql);
if (!$resultado) die("Error en la consulta: " . mysqli_error($conexion));

$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $idServicio = $fila['id_servicio'];
    if (!isset($datos[$idServicio])) {
        $datos[$idServicio] = [
            'servicio' => $fila['servicio'],
            'profesionales' => []
        ];
    }
    if ($fila['id_profesional']) {
        $datos[$idServicio]['profesionales'][] = [
            'id' => $fila['id_profesional'],
            'nombre' => $fila['nombre_prof'] . ' ' . $fila['apellido_prof']
        ];
    }
}

// ------------------- FILTROS DESDE GET -------------------
$filtro_servicio = isset($_GET['servicio']) ? intval($_GET['servicio']) : 0;
$filtro_profesional = isset($_GET['profesional']) ? intval($_GET['profesional']) : 0;

// ------------------- RESET AUTOMÁTICO DE PROFESIONAL INVÁLIDO -------------------
if ($filtro_servicio > 0 && $filtro_profesional > 0) {
    $prof_valido = false;
    if (isset($datos[$filtro_servicio])) {
        foreach ($datos[$filtro_servicio]['profesionales'] as $prof) {
            if ($prof['id'] == $filtro_profesional) {
                $prof_valido = true;
                break;
            }
        }
    }
    if (!$prof_valido) $filtro_profesional = 0;
}

// ------------------- CONSULTA PRINCIPAL: HORARIOS DISPONIBLES -------------------
$mostrar_turnos = false;
if ($filtro_servicio > 0) {
    $mostrar_turnos = true;

    $sql = "
        SELECT h.id_horario, h.dia, h.hora,
               p.id_profesional, p.nombre AS nombre_prof, p.apellido AS apellido_prof,
               s.id_servicio, s.nombre AS nombre_servicio
        FROM horarios h
        INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
        INNER JOIN servicios s ON p.id_servicio = s.id_servicio
        LEFT JOIN turnos t ON h.id_horario = t.id_horario
        WHERE t.id_turno IS NULL
          AND (h.dia > CURDATE() OR (h.dia = CURDATE() AND h.hora > CURTIME()))
          AND s.id_servicio = $filtro_servicio
    ";

    if ($filtro_profesional > 0) $sql .= " AND p.id_profesional = $filtro_profesional";

    $sql .= " ORDER BY h.dia, h.hora";

    $resultado_turnos = mysqli_query($conexion, $sql);
    if (!$resultado_turnos) die("Error en la consulta: " . mysqli_error($conexion));
}

// ------------------- MENSAJES DE RESERVA -------------------
if (isset($_GET['success'])) echo "<p style='color:green;'>Turno reservado correctamente.</p>";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'ocupado') echo "<p style='color:red;'>El turno ya fue reservado por otro usuario.</p>";
    else echo "<p style='color:red;'>Ocurrió un error al reservar el turno.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turnos disponibles</title>
</head>
<body>
<a href='../usuario/inicio.php'><-- Volver</a><br>
<h1>Buscar turnos</h1>

<!-- ------------------- FORMULARIO DE SELECCIÓN ------------------- -->
<form method="get">
    <label>Servicio (obligatorio):</label><br>
    <select name="servicio" required onchange="this.form.submit()">
        <option value="">-- Seleccione un servicio --</option>
        <?php foreach ($datos as $id => $info): ?>
            <option value="<?= $id ?>" <?= $filtro_servicio==$id?'selected':'' ?>>
                <?= htmlspecialchars($info['servicio']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <?php if ($filtro_servicio > 0 && !empty($datos[$filtro_servicio]['profesionales'])): ?>
        <label>Profesional (opcional):</label><br>
        <select name="profesional" onchange="this.form.submit()">
            <option value="0">-- Cualquiera --</option>
            <?php foreach ($datos[$filtro_servicio]['profesionales'] as $prof): ?>
                <option value="<?= $prof['id'] ?>" <?= $filtro_profesional==$prof['id']?'selected':'' ?>>
                    <?= htmlspecialchars($prof['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
    <?php endif; ?>
</form>

<!-- ------------------- LISTA DE TURNOS DISPONIBLES ------------------- -->
<?php if ($mostrar_turnos): ?>
    <h2>Turnos disponibles</h2>

    <?php if (mysqli_num_rows($resultado_turnos) > 0): ?>
        <table border="1" style="border-collapse:collapse;text-align:center;">
            <tr>
                <th>Día</th>
                <th>Hora</th>
                <th>Servicio</th>
                <th>Profesional</th>
                <th>Acción</th>
            </tr>
            <?php while ($fila = mysqli_fetch_assoc($resultado_turnos)): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['dia']) ?></td>
                    <td><?= htmlspecialchars($fila['hora']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_servicio']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_prof'].' '.$fila['apellido_prof']) ?></td>
                    <td>
                        <form method="post" action="reservar.php">
                            <input type="hidden" name="id_horario" value="<?= $fila['id_horario'] ?>">
                            <button type="submit">Reservar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No hay turnos disponibles para este servicio y profesional.</p>
    <?php endif; ?>

<?php else: ?>
    <p>Seleccione un servicio para ver los turnos disponibles.</p>
<?php endif; ?>

</body>
</html>