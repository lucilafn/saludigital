<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    echo'<script>
    alert("Para ingresar debe tener una sesion iniciada");
    window.location.href = "../usuario/form_iniciosesion.php";
    </script>';
}

$sql = "SELECT * FROM turnos";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turnos</title>
    <link rel="stylesheet" href="tabla.css">
</head>
<body>

<?php
//buscamos todos los turnos y los mostramos
if (mysqli_num_rows($resultado) > 0) {
    echo "<table>";
    echo "<tr>
            <th>Dia</th>
            <th>Hora</th>
            <th>Servicio</th>
            <th>Profesional</th>
            <th>Estado</th>
          </tr>";

    //mostramos los datos de los turnos
    while ($array = mysqli_fetch_assoc($resultado)) {
        if ($array["estado"] == 0)
        {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($array["dia"]) . "</td>";
            echo "<td>" . htmlspecialchars($array["hora"]) . "</td>";
            echo "<td>" . htmlspecialchars($array[""]) . "</td>";
            echo "<td>" . htmlspecialchars($array[""]) . "</td>";

              echo "<td id='fondo_reserva_disponible'><form action='reservar.php' method='post'>
                        <input hidden type='number' name='id_turno' value='".$array["id_turno"]."'>
                        <input type='submit' value='Reservar'>
                </form></td>";
        echo "</tr>";
        }
        echo "</table>";
    }
    
} else {
    //mostramos si no hay datos en "turnos"
    echo "<p style='text-align:center;'>No se encontraron resultados.</p>";
}
?>
</body>
</html>