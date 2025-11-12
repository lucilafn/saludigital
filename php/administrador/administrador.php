<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICAR SI ES ADMIN -------------------
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ------------------- FECHA DEL DÍA -------------------
$hoy = date('Y-m-d');

// ------------------- TURNOS DEL DÍA -------------------
$sql_turnos = "
    SELECT t.id_turno, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, u.dni,
           s.nombre AS servicio, p.nombre AS nombre_profesional, p.apellido AS apellido_profesional, h.hora
    FROM turnos t
    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
    INNER JOIN horarios h ON t.id_horario = h.id_horario
    INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
    INNER JOIN servicios s ON p.id_servicio = s.id_servicio
    WHERE h.dia = '$hoy'
    ORDER BY h.hora ASC";
$res_turnos = mysqli_query($conexion, $sql_turnos);

// ------------------- BUSCAR PACIENTE -------------------
$filtro_paciente = "";
$paciente_encontrado = null;
if (isset($_GET['buscar'])) {
    $filtro_paciente = trim($_GET['buscar']);
    if ($filtro_paciente !== "") {
        $sql_pac = "SELECT id_usuario, nombre, apellido, dni FROM usuarios 
                    WHERE rol = 0 AND (apellido LIKE ? OR dni LIKE ?) LIMIT 10";
        $stmt = $conexion->prepare($sql_pac);
        $like = "%$filtro_paciente%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $paciente_encontrado = $stmt->get_result();
    }
}

// ------------------- FILTROS DE SERVICIO Y PROFESIONAL -------------------
$sql = "SELECT s.id_servicio, s.nombre AS servicio,
               p.id_profesional, p.nombre AS nombre_prof, p.apellido AS apellido_prof
        FROM servicios s
        LEFT JOIN profesionales p ON s.id_servicio = p.id_servicio
        ORDER BY s.nombre, p.nombre, p.apellido";
$resultado = mysqli_query($conexion, $sql);
$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $idS = $fila['id_servicio'];
    if (!isset($datos[$idS])) {
        $datos[$idS] = ['servicio' => $fila['servicio'], 'profesionales' => []];
    }
    if ($fila['id_profesional']) {
        $datos[$idS]['profesionales'][] = [
            'id' => $fila['id_profesional'],
            'nombre' => $fila['nombre_prof'].' '.$fila['apellido_prof']
        ];
    }
}

// ------------------- FILTROS DESDE GET -------------------
$filtro_servicio = isset($_GET['servicio']) ? intval($_GET['servicio']) : 0;
$filtro_profesional = isset($_GET['profesional']) ? intval($_GET['profesional']) : 0;
$filtro_usuario = isset($_GET['paciente']) ? intval($_GET['paciente']) : 0;

// ------------------- CONSULTA PRINCIPAL: HORARIOS DISPONIBLES -------------------
$mostrar_turnos = false;
if ($filtro_servicio > 0) {
    $mostrar_turnos = true;
    $sql_turnos_disp = "
        SELECT h.id_horario, h.dia, h.hora,
               p.id_profesional, p.nombre AS nombre_prof, p.apellido AS apellido_prof,
               s.id_servicio, s.nombre AS nombre_servicio
        FROM horarios h
        INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
        INNER JOIN servicios s ON p.id_servicio = s.id_servicio
        LEFT JOIN turnos t ON h.id_horario = t.id_horario
        WHERE t.id_turno IS NULL
          AND (h.dia > CURDATE() OR (h.dia = CURDATE() AND h.hora > CURTIME()))
          AND s.id_servicio = $filtro_servicio";
    if ($filtro_profesional > 0) $sql_turnos_disp .= " AND p.id_profesional = $filtro_profesional";
    $sql_turnos_disp .= " ORDER BY h.dia, h.hora";
    $resultado_turnos = mysqli_query($conexion, $sql_turnos_disp);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Panel Administrador</title>
</head>
<body>
<a href='pacientes.php'>Pacientes</a>
<a href='profesionales.php'>Profesionales</a>
<a href='horarios.php'>Horarios</a>

<h2>Agendar turno para un paciente</h2>

<!-- BUSCADOR DE PACIENTE -->
<form method="get">
    <label>Buscar paciente (apellido o DNI):</label><br>
    <input type="text" name="buscar" value="<?= htmlspecialchars($filtro_paciente) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if ($paciente_encontrado): ?>
    <h3>Resultados:</h3>
    <ul>
    <?php while ($fila = $paciente_encontrado->fetch_assoc()): ?>
        <li>
            <?= htmlspecialchars($fila['apellido'].", ".$fila['nombre']." (DNI: ".$fila['dni'].")") ?>
            <a href="administrador.php?paciente=<?= $fila['id_usuario'] ?>">Seleccionar</a>
        </li>
    <?php endwhile; ?>
    </ul>
<?php endif; ?>

<?php if ($filtro_usuario > 0): ?>
    <hr>
    <h3>Paciente seleccionado: 
        <?php
        $r = mysqli_query($conexion, "SELECT nombre, apellido, dni FROM usuarios WHERE id_usuario=$filtro_usuario");
        $p = mysqli_fetch_assoc($r);
        echo htmlspecialchars($p['apellido'].", ".$p['nombre']." (DNI ".$p['dni'].")");
        ?>
    </h3>

    <!-- FORMULARIO IGUAL A PEDIRTURNO -->
    <form method="get">
        <input type="hidden" name="paciente" value="<?= $filtro_usuario ?>">

        <label>Servicio (obligatorio):</label><br>
        <select name="servicio" required onchange="this.form.submit()">
            <option value="">-- Seleccione un servicio --</option>
            <?php foreach ($datos as $id => $info): ?>
                <option value="<?= $id ?>" <?= $filtro_servicio==$id?'selected':'' ?>>
                    <?= htmlspecialchars($info['servicio']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <?php if ($filtro_servicio > 0 && !empty($datos[$filtro_servicio]['profesionales'])): ?>
            <label>Profesional (opcional):</label><br>
            <select name="profesional" onchange="this.form.submit()">
                <option value="0">-- Cualquiera --</option>
                <?php foreach ($datos[$filtro_servicio]['profesionales'] as $prof): ?>
                    <option value="<?= $prof['id'] ?>" <?= $filtro_profesional==$prof['id']?'selected':'' ?>>
                        <?= htmlspecialchars($prof['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
        <?php endif; ?>
    </form>

    <!-- LISTA DE TURNOS DISPONIBLES -->
    <?php if ($mostrar_turnos): ?>
        <h3>Turnos disponibles</h3>
        <?php if (mysqli_num_rows($resultado_turnos) > 0): ?>
            <table border="1" style="border-collapse:collapse;text-align:center;">
                <tr>
                    <th>Día</th><th>Hora</th><th>Servicio</th><th>Profesional</th><th>Acción</th>
                </tr>
                <?php while ($fila = mysqli_fetch_assoc($resultado_turnos)): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['dia']) ?></td>
                        <td><?= htmlspecialchars($fila['hora']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_servicio']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_prof'].' '.$fila['apellido_prof']) ?></td>
                        <td>
                            <form method="post" action="reservar_admin.php">
                                <input type="hidden" name="id_horario" value="<?= $fila['id_horario'] ?>">
                                <input type="hidden" name="id_usuario" value="<?= $filtro_usuario ?>">
                                <button type="submit">Reservar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay turnos disponibles para este servicio/profesional.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Seleccione un servicio para ver los turnos disponibles.</p>
    <?php endif; ?>
<?php endif; ?>

<hr>

<h1>Turnos del día <?= date('d/m/Y'); ?></h1>

<table border="1" style="border-collapse:collapse;text-align:center;">
<tr>
    <th>ID Turno</th><th>Paciente</th><th>DNI</th><th>Servicio</th><th>Profesional</th><th>Hora</th>
</tr>
<?php
if (mysqli_num_rows($res_turnos) > 0) {
    while ($fila = mysqli_fetch_assoc($res_turnos)) {
        echo "<tr>
                <td>{$fila['id_turno']}</td>
                <td>{$fila['nombre_usuario']} {$fila['apellido_usuario']}</td>
                <td>{$fila['dni']}</td>
                <td>{$fila['servicio']}</td>
                <td>{$fila['nombre_profesional']} {$fila['apellido_profesional']}</td>
                <td>{$fila['hora']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No hay turnos registrados para hoy.</td></tr>";
}
?>
</table>
</body>
</html>