<?php
require_once('../conexion.php');
session_start();

// ------------------- VERIFICAR ADMIN -------------------
if (!isset($_SESSION['idusuario']) || $_SESSION['administrador'] != 1) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// ------------------- FILTRO DE BÚSQUEDA -------------------
$filtro = "";
if (isset($_GET['buscar'])) {
    $filtro = trim($_GET['buscar']);
}

$sql = "SELECT id_usuario, nombre, apellido, dni, telefono, obra_social, email
        FROM usuarios
        WHERE rol = 0";

if ($filtro !== "") {
    $like = "%$filtro%";
    $stmt = $conexion->prepare($sql . " AND (apellido LIKE ? OR dni LIKE ?)");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = mysqli_query($conexion, $sql . " ORDER BY apellido, nombre");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/pedirturno.css">
<title>Pacientes registrados</title>
</head>
<body>

<a href="administrador.php">Turnos</a>
<a href="profesionales.php">Profesionales</a>
<a href="horarios.php">Horarios</a>

<h2>Registrar nuevo paciente</h2>

<form method="post" action="registrar_paciente.php">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required autocomplete="off"><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" required autocomplete="off"><br><br>

    <label>DNI:</label><br>
    <input type="number" name="dni" required autocomplete="off"><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" autocomplete="off"><br><br>

    <label>Obra social:</label><br>
    <input type="text" name="obra_social" autocomplete="off"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required autocomplete="off"><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="clave" required autocomplete="off"><br><br>

    <button type="submit">Crear paciente</button>
</form>

<hr>

<h1>Pacientes registrados</h1>

<!-- BUSCADOR -->
<form method="get" style="margin-bottom:15px;">
    <label>Buscar por apellido o DNI:</label>
    <input type="text" name="buscar" value="<?= htmlspecialchars($filtro) ?>">
    <button type="submit">Buscar</button>
</form>

<table border="1" style="border-collapse:collapse;text-align:center;">
<tr>
    <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th>
    <th>Teléfono</th><th>Obra social</th><th>Email</th>
</tr>

<?php
if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>
                <td>{$fila['id_usuario']}</td>
                <td>".htmlspecialchars($fila['nombre'])."</td>
                <td>".htmlspecialchars($fila['apellido'])."</td>
                <td>".htmlspecialchars($fila['dni'])."</td>
                <td>".htmlspecialchars($fila['telefono'])."</td>
                <td>".htmlspecialchars($fila['obra_social'])."</td>
                <td>".htmlspecialchars($fila['email'])."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No se encontraron pacientes.</td></tr>";
}
?>
</table>

</body>
</html>