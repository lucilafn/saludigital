<?php
include('../conexion.php');
session_start();

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
            <th>Cita</th>
            <th>Categoría</th>
            <th>Disponibilidad</th>
          </tr>";

    //mostramos los datos de los turnos
    while ($array = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($array["horario"]) . "</td>";
        echo "<td>" . htmlspecialchars($array["categoria"]) . "</td>";

        //aca consultamos el estado del turno, en caso de estar reservado (true) no imprimimos el boton y dejamos su fondo en rojo
        if ($array["reservado"]) {
            //reservado
            echo "<td id='fondo_reserva_reservado'>Turno Reservado</td>";
        } else {
            //no reservado
            echo "<td id='fondo_reserva_disponible'><form action='reservar.php' method='post'>
                        <input hidden type='number' name='id' value='".$array["id"]."'>
                        <input type='submit' value='Reservar'>
                </form></td>";
        }

        echo "</tr>";
    }
    //cerramos la tabla
    echo "</table>";
} else {
    //mostramos si no hay datos en "turnos"
    echo "<p style='text-align:center;'>No se encontraron resultados.</p>";
}
?>
</body>
</html>