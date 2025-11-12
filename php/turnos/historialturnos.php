        <?php
            // Incluye la conexión a la base de datos una sola vez e inicia la sesión
            require_once('../conexion.php');
            session_start();

            // Verifica si el usuario ha iniciado sesión; si no, lo redirige al formulario de inicio de sesión
            if (!isset($_SESSION['usuario']))
            {
                header("Location: ../usuario/form_iniciosesion.php");
                exit();
            }
        ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/historialturnos.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>historial</title>
    </head>
    <body>
        <a href='../usuario/perfil.php'>Perfil</a>
        <a href='pedirturno.php'>Pedir Turno</a>
        <?php
            echo"<h2>Historial de turnos: </h2>";
            echo"<h3>Aqui se encuentran los turnos que han expirado</h3>";

            $id_usuario = $_SESSION['idusuario']; // Toma el id del usuario logueado

            // Solo mostrar turnos cuando el día ya pasó, o el dia es el actual pero la hora ya pasó
            $sql =  "SELECT t.id_turno,
                            p.nombre AS doctor_nombre, p.apellido AS doctor_apellido, 
                            s.nombre AS servicio, h.dia, h.hora
                    FROM turnos t /* desde la tabla `turnos` */
                    INNER JOIN horarios h ON t.id_horario = h.id_horario /* se junta con `horarios` mediante 'id_horario' */
                    INNER JOIN profesionales p ON h.id_profesional = p.id_profesional /* mediante el INNER JOIN con `horarios`, se junta con `profesionales` mediante id */
                    INNER JOIN servicios s ON p.id_servicio = s.id_servicio /* y finalmente se junta con `servicios` */
                    WHERE t.id_usuario = $id_usuario /* filtra por el usuario logueado */
                    AND (h.dia < CURDATE() OR (h.dia = CURDATE() AND h.hora < CURTIME())) /* filtra por turnos pasados */
                    ORDER BY h.dia DESC, h.hora DESC"; /* ordena por fecha y hora descendente */
            
            $resultado = mysqli_query($conexion, $sql);

            if (mysqli_num_rows($resultado) > 0)
            {
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>Profesional</th>";
                echo "<th>Servicio</th>";
                echo "<th>Fecha</th>";
                echo "<th>Hora</th>";
                echo "</tr>";

                // Mientras haya turnos en el historial, los muestra en la tabla
                while ($arreglo = mysqli_fetch_assoc($resultado))
                {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($arreglo['doctor_nombre']) . " " . htmlspecialchars($arreglo['doctor_apellido']) . "</td>";
                    echo "<td>" . htmlspecialchars($arreglo['servicio']) . "</td>";
                    echo "<td>" . htmlspecialchars($arreglo['dia']) . "</td>";
                    echo "<td>" . htmlspecialchars($arreglo['hora']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Si no hay turnos en el historial, muestra un mensaje
            else
            {
                echo "<p>No tienes turnos en tu historial.</p>";
            }
        ?>
    </body>
</html>