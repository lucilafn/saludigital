<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICAR ADMIN -------------------
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ------------------- OBTENER ID PROFESIONAL -------------------
if (!isset($_GET['id'])) {
    echo "ID de profesional no especificado.";
    exit();
}

$id_profesional = intval($_GET['id']);

// ------------------- TRAER SERVICIOS -------------------
$sql_serv = "SELECT id_servicio, nombre FROM servicios ORDER BY nombre";
$res_serv = mysqli_query($conexion, $sql_serv);

// ------------------- OBTENER DATOS DEL PROFESIONAL -------------------
$sql = "SELECT * FROM profesionales WHERE id_profesional = $id_profesional";
$res = mysqli_query($conexion, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    echo "Profesional no encontrado.";
    exit();
}
$prof = mysqli_fetch_assoc($res);

// Convertir días a array
$dias_guardados = explode(",", $prof['dias_trabajados']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Editar profesional</title>
<script>
function actualizarDias() {
    const checkboxes = document.querySelectorAll('input[name="dias_trabajados[]"]:checked');
    const seleccionados = Array.from(checkboxes).map(ch => ch.value);
    document.getElementById('dias_hidden').value = seleccionados.join(',');
}
</script>
</head>
<body>

<a href="profesionales.php"><-- Volver</a><br><br>
<h1>Editar profesional</h1>

<form method="post" action="actualizar_profesional.php" onsubmit="actualizarDias()">
    <input type="hidden" name="id_profesional" value="<?= $id_profesional ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($prof['nombre']) ?>" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" value="<?= htmlspecialchars($prof['apellido']) ?>" required><br><br>

    <label>Servicio:</label><br>
    <select name="id_servicio" required>
        <?php while ($serv = mysqli_fetch_assoc($res_serv)): ?>
            <option value="<?= $serv['id_servicio'] ?>" <?= $serv['id_servicio'] == $prof['id_servicio'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($serv['nombre']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Días trabajados:</label><br>
    <?php
    $dias = ['lunes','martes','miércoles','jueves','viernes','sábado','domingo'];
    foreach ($dias as $dia) {
        $checked = in_array($dia, $dias_guardados) ? "checked" : "";
        echo "<label><input type='checkbox' name='dias_trabajados[]' value='$dia' $checked onchange='actualizarDias()'> $dia </label> ";
    }
    ?>
    <input type="hidden" name="dias_trabajados_texto" id="dias_hidden" value="<?= htmlspecialchars($prof['dias_trabajados']) ?>"><br><br>

    <label>Hora inicio:</label><br>
    <input type="time" name="hora_inicio" value="<?= $prof['hora_inicio'] ?>" required><br><br>

    <label>Hora fin:</label><br>
    <input type="time" name="hora_fin" value="<?= $prof['hora_fin'] ?>" required><br><br>

    <label>Intervalo (minutos):</label><br>
    <input type="number" name="intervalo" value="<?= $prof['intervalo'] ?>" required><br><br>

    <button type="submit">Guardar cambios</button>
</form>

</body>
</html>
