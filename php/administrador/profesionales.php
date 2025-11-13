<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICAR ADMIN -------------------
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ------------------- TRAER SERVICIOS -------------------
$sql_serv = "SELECT id_servicio, nombre FROM servicios ORDER BY nombre";
$res_serv = mysqli_query($conexion, $sql_serv);

// ------------------- TRAER PROFESIONALES -------------------
$sql_prof = "SELECT p.id_profesional, p.nombre, p.apellido, p.dias_trabajados, p.hora_inicio, p.hora_fin, p.intervalo, s.nombre AS servicio
              FROM profesionales p
              LEFT JOIN servicios s ON p.id_servicio = s.id_servicio
              ORDER BY p.apellido, p.nombre";
$res_prof = mysqli_query($conexion, $sql_prof);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Gestión de Profesionales</title>
<script>
// Función para manejar el array de días seleccionados
function actualizarDias() {
    const checkboxes = document.querySelectorAll('input[name="dias_trabajados[]"]:checked');
    const seleccionados = Array.from(checkboxes).map(ch => ch.value);
    document.getElementById('dias_hidden').value = seleccionados.join(',');
}
</script>
</head>
<body>

<a href="administrador.php">Turnos</a> |
<a href="pacientes.php">Pacientes</a> |
<a href="horarios.php">Horarios</a><br>

<h1>Gestión de Profesionales</h1>

<!-- ==================== FORMULARIO NUEVO PROFESIONAL ==================== -->
<h2>Registrar nuevo profesional</h2>

<form method="post" action="registrar_profesional.php" onsubmit="actualizarDias()">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" required><br><br>

    <label>Servicio:</label><br>
    <select name="id_servicio" required>
        <option value="">-- Seleccione un servicio --</option>
        <?php while ($serv = mysqli_fetch_assoc($res_serv)): ?>
            <option value="<?= $serv['id_servicio'] ?>"><?= htmlspecialchars($serv['nombre']) ?></option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label>Días trabajados:</label><br>
    <?php
    $dias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
    foreach ($dias as $dia) {
        echo "<label><input type='checkbox' name='dias_trabajados[]' value='$dia' onchange='actualizarDias()'> $dia </label> ";
    }
    ?>
    <input type="hidden" name="dias_trabajados_texto" id="dias_hidden"><br><br>

    <label>Hora inicio:</label><br>
    <input type="time" name="hora_inicio" required><br><br>

    <label>Hora fin:</label><br>
    <input type="time" name="hora_fin" required><br><br>

    <label>Intervalo (minutos):</label><br>
    <input type="number" name="intervalo" required min="5"><br><br>

    <button type="submit">Registrar profesional</button>
</form>

<hr>

<!-- ==================== LISTADO DE PROFESIONALES ==================== -->
<h2>Profesionales registrados</h2>

<table border="1" style="border-collapse:collapse;text-align:center;">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Servicio</th>
    <th>Días trabajados</th>
    <th>Hora inicio</th>
    <th>Hora fin</th>
    <th>Intervalo (min)</th>
    <th>Acciones</th>
</tr>

<?php
if (mysqli_num_rows($res_prof) > 0) {
    while ($fila = mysqli_fetch_assoc($res_prof)) {
        echo "<tr>
                <td>{$fila['id_profesional']}</td>
                <td>".htmlspecialchars($fila['nombre'])."</td>
                <td>".htmlspecialchars($fila['apellido'])."</td>
                <td>".htmlspecialchars($fila['servicio'])."</td>
                <td>".htmlspecialchars($fila['dias_trabajados'])."</td>
                <td>".htmlspecialchars($fila['hora_inicio'])."</td>
                <td>".htmlspecialchars($fila['hora_fin'])."</td>
                <td>".htmlspecialchars($fila['intervalo'])."</td>
                <td>
                    <form method='get' action='editar_profesional.php' style='display:inline;'>
                        <input type='hidden' name='id' value='{$fila['id_profesional']}'>
                        <button type='submit'>Modificar</button>
                    </form>
                    <form method='post' action='eliminar_profesional.php' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar este profesional?\")'>
                        <input type='hidden' name='id' value='{$fila['id_profesional']}'>
                        <button type='submit'>Eliminar</button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No hay profesionales registrados.</td></tr>";
}
?>
</table>

</body>
</html>