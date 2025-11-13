<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICACIÓN DE SESIÓN -------------------
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ------------------- ELIMINAR PACIENTE -------------------
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM usuarios WHERE id_usuario = $id AND rol = 0");
    echo "<p style='color:green;'>Paciente eliminado correctamente.</p>";
}

// ------------------- BÚSQUEDA -------------------
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = mysqli_real_escape_string($conexion, $_GET['buscar']);
    $sql = "SELECT id_usuario, nombre, apellido, dni, telefono, obra_social, email 
            FROM usuarios 
            WHERE rol = 0 AND (apellido LIKE '%$busqueda%' OR dni LIKE '%$busqueda%')
            ORDER BY id_usuario ASC";
} else {
    $sql = "SELECT id_usuario, nombre, apellido, dni, telefono, obra_social, email 
            FROM usuarios 
            WHERE rol = 0 
            ORDER BY id_usuario ASC";
}

$result = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Pacientes</title>
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
form.busqueda input[type="text"] {
    padding: 4px;
    width: 220px;
}
</style>
</head>
<body>

<a href="administrador.php">Turnos</a> | 
<a href="profesionales.php">Profesionales</a> | 
<a href="horarios.php">Horarios</a><br>

<!-- ------------------- FORMULARIO REGISTRO ------------------- -->
<h1>Registrar nuevo paciente</h1>
<form method="post" action="registrar_paciente.php">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" required><br><br>

    <label>DNI:</label><br>
    <input type="text" name="dni" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" required><br><br>

    <label>Obra social:</label><br>
    <input type="text" name="obra_social"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required autocomplete="off"><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="contrasenia" required autocomplete="off"><br><br>

    <button type="submit">Registrar paciente</button>
</form>

<hr>

<h1>Gestión de Pacientes</h1>

<!-- ------------------- FORMULARIO DE BÚSQUEDA ------------------- -->
<form method="get" class="busqueda">
    <label>Buscar por Apellido o DNI:</label>
    <input type="text" name="buscar" value="<?= htmlspecialchars($busqueda) ?>">
    <button type="submit">Buscar</button>
    <a href="paciente.php">Limpiar</a>
</form>

<hr>

<!-- ------------------- TABLA PACIENTES ------------------- -->
<h2>Pacientes registrados</h2>
<table>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>DNI</th>
    <th>Teléfono</th>
    <th>Obra Social</th>
    <th>Email</th>
    <th>Acciones</th>
</tr>
<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id_usuario'] ?></td>
        <td><?= htmlspecialchars($row['nombre']) ?></td>
        <td><?= htmlspecialchars($row['apellido']) ?></td>
        <td><?= htmlspecialchars($row['dni']) ?></td>
        <td><?= htmlspecialchars($row['telefono']) ?></td>
        <td><?= htmlspecialchars($row['obra_social']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
            <a href="paciente.php?eliminar=<?= $row['id_usuario'] ?>" 
               onclick="return confirm('¿Seguro que desea eliminar este paciente?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="8">No se encontraron pacientes registrados.</td></tr>
<?php endif; ?>
</table>

</body>
</html>