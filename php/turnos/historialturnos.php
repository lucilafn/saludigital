<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/historialturnos.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>historial</title>
</head>
<body>

<?php
  include('../conexion.php');
  session_start();

    if (!isset($_SESSION['usuario']))
    {
        header("Location: ../usuario/form_iniciosesion.php");
        exit();
    }

    echo"<h2>Historial de turnos: </h2>";
    echo"<h3>Aqui se encuentran los turnos que han expirado</h3>";

    $id_usuario = $_SESSION['idusuario'];
    
    // solo mostrar turnos cuando el día ya pasó, o si es hoy, cuando la hora ya pasó
        $sql = "SELECT t.id_turno, p.nombre AS doctor_nombre, p.apellido AS doctor_apellido, 
               s.nombre AS servicio, h.dia, h.hora
        FROM turnos t
        INNER JOIN horarios h ON t.id_horario = h.id_horario
        INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
        INNER JOIN servicios s ON p.id_servicio = s.id_servicio
        WHERE t.id_usuario = $id_usuario
        AND (h.dia < CURDATE() OR (h.dia = CURDATE() AND h.hora < CURTIME()))
        ORDER BY h.dia DESC, h.hora DESC";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) > 0)
    {
        echo "<table border='1'>
                <tr>
                    <th>Profesional</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>";

        while ($arreglo = mysqli_fetch_assoc($resultado))
        {
            echo "<tr>
                    <td>" . htmlspecialchars($arreglo['doctor_nombre']) . " " . htmlspecialchars($arreglo['doctor_apellido']) . "</td>
                    <td>" . htmlspecialchars($arreglo['servicio']) . "</td>
                    <td>" . htmlspecialchars($arreglo['dia']) . "</td>
                    <td>" . htmlspecialchars($arreglo['hora']) . "</td>
                  </tr>";
        }
        echo "</table>";
    }
    else
    {
        echo "<p>No tienes turnos en tu historial.</p>";
    }
?>
</body>
</html>