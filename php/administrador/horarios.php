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

// Para usar strftime en español (Linux)
setlocale(LC_TIME, "es_ES.UTF-8", "Spanish_Spain", "Spanish");

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Generar horarios</title>
<style>
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    border: 1px solid #999;
    padding: 6px;
    text-align: center;
}
</style>
</head>
<body>

<a href="administrador.php">Turnos</a>
<a href="pacientes.php">Pacientes</a>
<a href="profesionales.php">Profesionales</a>

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
    <input type="date" name="fecha_inicio" required
        value="<?= isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '' ?>"><br><br>

    <label>Hasta (fecha final):</label><br>
    <input type="date" name="fecha_fin" required
        value="<?= isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '' ?>"><br><br>

    <button type="submit">Generar horarios</button>
</form>

<hr>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prof = intval($_POST['id_profesional']);
    $inicio = $_POST['fecha_inicio'];
    $fin = $_POST['fecha_fin'];

    if ($id_prof <= 0 || !$inicio || !$fin) {
        echo "<p style='color:red;'>Faltan datos.</p>";
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

    $diasTrabajados = explode(",", strtolower($prof['dias_trabajados']));
    $horaInicio = $prof['hora_inicio'];
    $horaFin = $prof['hora_fin'];
    $intervalo = intval($prof['intervalo']);

    $fechaActual = strtotime($inicio);
    $fechaFin = strtotime($fin);
    $insertados = 0;

    while ($fechaActual <= $fechaFin) {
        $nombreDia = strtolower(strftime('%A', $fechaActual));

        // Normalizar nombres de días (por compatibilidad Windows/Linux)
        $nombreDia = str_replace(['é','á'], ['e','a'], $nombreDia);

        if (in_array($nombreDia, $diasTrabajados)) {
            $hora = strtotime($horaInicio);
            $horaFinTimestamp = strtotime($horaFin);

            while ($hora < $horaFinTimestamp) {
                $fechaStr = date('Y-m-d', $fechaActual);
                $horaStr = date('H:i:s', $hora);

                // Verificar si ya existe ese horario
                $existe = mysqli_query($conexion, "SELECT id_horario FROM horarios WHERE id_profesional=$id_prof AND dia='$fechaStr' AND hora='$horaStr'");
                if (mysqli_num_rows($existe) == 0) {
                    mysqli_query($conexion, "INSERT INTO horarios (id_profesional, dia, hora) VALUES ($id_prof, '$fechaStr', '$horaStr')");
                    $insertados++;
                }

                $hora = strtotime("+$intervalo minutes", $hora);
            }
        }
        $fechaActual = strtotime("+1 day", $fechaActual);
    }

    echo "<p style='color:green;'>Se generaron $insertados horarios correctamente para <b>{$prof['apellido']}, {$prof['nombre']}</b>.</p>";

    // ------------------- MOSTRAR HORARIOS GENERADOS -------------------
    $sql_hor = "SELECT dia, hora FROM horarios 
                WHERE id_profesional=$id_prof 
                AND dia BETWEEN '$inicio' AND '$fin' 
                ORDER BY dia, hora";
    $res_hor = mysqli_query($conexion, $sql_hor);

    if (mysqli_num_rows($res_hor) > 0) {
        echo "<h2>Horarios generados</h2>";
        echo "<table>";
        echo "<tr><th>Fecha</th><th>Hora</th></tr>";

        while ($h = mysqli_fetch_assoc($res_hor)) {
            echo "<tr><td>{$h['dia']}</td><td>{$h['hora']}</td></tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No hay horarios registrados en ese rango.</p>";
    }
}
?>

</body>
</html>