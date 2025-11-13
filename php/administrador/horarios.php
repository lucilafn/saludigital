<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICAR ADMIN -------------------
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ------------------- CARGAR PROFESIONALES -------------------
$sql_prof = "SELECT id_profesional, nombre, apellido FROM profesionales ORDER BY apellido, nombre";
$res_prof = mysqli_query($conexion, $sql_prof);

// ------------------- FUNCIONES UTILES -------------------
/**
 * Normaliza texto: pasa a minúsculas y quita tildes.
 * Úsalo para comparar nombres de días guardados en la BD.
 */
function normalizar($txt) {
    $txt = mb_strtolower(trim($txt), 'UTF-8');
    $buscar  = ['á','é','í','ó','ú','ü'];
    $reempl = ['a','e','i','o','u','u'];
    return str_replace($buscar, $reempl, $txt);
}

/**
 * Devuelve el nombre del día en español (sin tildes) a partir de un timestamp.
 * Ej: 'miércoles' -> 'miercoles'
 */
function nombreDiaEspSinTilde($ts) {
    // date('l') devuelve english day names: Monday, Tuesday...
    $eng = date('l', $ts);
    $map = [
        'Monday'    => 'lunes',
        'Tuesday'   => 'martes',
        'Wednesday' => 'miercoles',
        'Thursday'  => 'jueves',
        'Friday'    => 'viernes',
        'Saturday'  => 'sabado',
        'Sunday'    => 'domingo'
    ];
    return isset($map[$eng]) ? $map[$eng] : normalizar($eng);
}

// Forzar timezone coherente (opcional — ajústalo si tu servidor usa otra zona)
date_default_timezone_set('America/Argentina/Buenos_Aires');
$hoy = date('Y-m-d');
$ahora_ts = time();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Generar horarios</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #999; padding: 6px; text-align: center; }
</style>
</head>
<body>

<a href="administrador.php">Turnos</a> | 
<a href="pacientes.php">Pacientes</a> | 
<a href="profesionales.php">Profesionales</a><br>

<h1>Generar horarios automáticos</h1>

<form method="post">
    <label>Profesional:</label><br>
    <select name="id_profesional" required>
        <option value="">-- Seleccione --</option>
        <?php while ($p = mysqli_fetch_assoc($res_prof)): ?>
            <option value="<?= $p['id_profesional'] ?>"
                <?= (isset($_POST['id_profesional']) && $_POST['id_profesional'] == $p['id_profesional']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['apellido'] . ", " . $p['nombre']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Desde (fecha inicial):</label><br>
    <input type="date" name="fecha_inicio" required min="<?= date('Y-m-d') ?>"
        value="<?= isset($_POST['fecha_inicio']) ? htmlspecialchars($_POST['fecha_inicio']) : '' ?>"><br><br>

    <label>Hasta (fecha final):</label><br>
    <input type="date" name="fecha_fin" required min="<?= date('Y-m-d') ?>"
        value="<?= isset($_POST['fecha_fin']) ? htmlspecialchars($_POST['fecha_fin']) : '' ?>"><br><br>

    <button type="submit">Generar horarios</button>
</form>

<hr>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prof = intval($_POST['id_profesional']);
    $inicio = $_POST['fecha_inicio'];
    $fin = $_POST['fecha_fin'];

    // Validaciones básicas
    if ($id_prof <= 0 || !$inicio || !$fin) {
        echo "<p style='color:red;'>Faltan datos.</p>";
        exit();
    }

    // No permitir fechas pasadas ni rango inválido
    if ($inicio < $hoy) {
        echo "<p style='color:red;'>La fecha inicial no puede ser anterior a hoy.</p>";
        exit();
    }
    if ($fin < $inicio) {
        echo "<p style='color:red;'>La fecha final no puede ser anterior a la inicial.</p>";
        exit();
    }

    // ------------------- OBTENER DATOS DEL PROFESIONAL -------------------
    $q = "SELECT nombre, apellido, dias_trabajados, hora_inicio, hora_fin, intervalo 
          FROM profesionales WHERE id_profesional = $id_prof";
    $r = mysqli_query($conexion, $q);
    if (!$r || mysqli_num_rows($r) == 0) {
        echo "<p style='color:red;'>Profesional no encontrado.</p>";
        exit();
    }
    $prof = mysqli_fetch_assoc($r);

    // Normalizar dias guardados en BD (sin tildes, minúsculas)
    $diasTrabajadosRaw = $prof['dias_trabajados'];
    $diasTrabajados = array_map('trim', explode(',', normalizar($diasTrabajadosRaw)));

    $horaInicio = $prof['hora_inicio']; // ej "10:00:00"
    $horaFin = $prof['hora_fin'];       // ej "18:00:00"
    $intervalo = intval($prof['intervalo']);
    if ($intervalo <= 0) $intervalo = 30; // fallback

    $fechaActual_ts = strtotime($inicio);
    $fechaFin_ts = strtotime($fin);
    $insertados = 0;

    // Recorrer cada fecha del rango
    while ($fechaActual_ts <= $fechaFin_ts) {
        $fechaStr = date('Y-m-d', $fechaActual_ts);

        // Obtener nombre del día en español sin tildes (lunes, martes, miercoles, etc.)
        $nombreDia = nombreDiaEspSinTilde($fechaActual_ts);

        // Si el profesional trabaja ese día
        if (in_array($nombreDia, $diasTrabajados)) {
            // Iterar horas desde horaInicio hasta horaFin en pasos de $intervalo
            $hora_ts = strtotime($horaInicio);
            $horaFin_ts = strtotime($horaFin);

            while ($hora_ts < $horaFin_ts) {
                $horaStr = date('H:i:s', $hora_ts);

                // COMPARACIÓN DE TIEMPO: no generar horarios ya pasados (en el día actual)
                // Convertir fecha+hora a timestamp
                $turno_datetime_ts = strtotime($fechaStr . ' ' . $horaStr);

                if ($turno_datetime_ts <= $ahora_ts) {
                    // si es una fecha/horario que ya pasó, lo saltamos
                    $hora_ts = strtotime("+{$intervalo} minutes", $hora_ts);
                    continue;
                }

                // Verificar si ya existe ese horario
                $dia_sql = mysqli_real_escape_string($conexion, $fechaStr);
                $hora_sql = mysqli_real_escape_string($conexion, $horaStr);
                $existe_sql = "SELECT id_horario FROM horarios WHERE id_profesional = $id_prof AND dia = '$dia_sql' AND hora = '$hora_sql'";
                $existe = mysqli_query($conexion, $existe_sql);

                if (!$existe) {
                    echo "<p style='color:red;'>Error en la consulta de verificación: " . mysqli_error($conexion) . "</p>";
                    exit();
                }

                if (mysqli_num_rows($existe) == 0) {
                    $ins_sql = "INSERT INTO horarios (id_profesional, dia, hora) VALUES ($id_prof, '$dia_sql', '$hora_sql')";
                    if (mysqli_query($conexion, $ins_sql)) {
                        $insertados++;
                    } else {
                        // no detenemos todo por un insert fallido, solo informamos
                        echo "<p style='color:orange;'>Advertencia: no se pudo insertar horario $dia_sql $hora_sql — " . mysqli_error($conexion) . "</p>";
                    }
                }

                $hora_ts = strtotime("+{$intervalo} minutes", $hora_ts);
            }
        }

        // avanzar al siguiente día
        $fechaActual_ts = strtotime('+1 day', $fechaActual_ts);
    }

    echo "<p style='color:green;'>Se generaron $insertados horarios correctamente para <b>" . htmlspecialchars($prof['apellido'] . ', ' . $prof['nombre']) . "</b>.</p>";

    // ------------------- MOSTRAR HORARIOS GENERADOS -------------------
    $sql_hor = "SELECT dia, hora FROM horarios 
                WHERE id_profesional=$id_prof 
                AND dia BETWEEN '$inicio' AND '$fin' 
                ORDER BY dia, hora";
    $res_hor = mysqli_query($conexion, $sql_hor);

    if ($res_hor && mysqli_num_rows($res_hor) > 0) {
        echo "<h2>Horarios generados</h2>";
        echo "<table>";
        echo "<tr><th>Fecha</th><th>Hora</th></tr>";
        while ($h = mysqli_fetch_assoc($res_hor)) {
            echo "<tr><td>" . htmlspecialchars($h['dia']) . "</td><td>" . htmlspecialchars($h['hora']) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay horarios registrados en ese rango.</p>";
    }
}
?>

</body>
</html>