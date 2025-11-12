<?php
    // Incluye la conexión a la base de datos una sola vez e inicia la sesión
    require_once('../conexion.php');
    session_start();

    // Consulta: Servicios con los nombres y apellidos de sus profesionales y una descripción
    $sql =  "SELECT s.id_servicio,
                    s.nombre AS servicio, 
                    s.descripcion, 
                    p.nombre AS nombre_profesional, 
                    p.apellido
            FROM servicios s /* desde la tabla `servicios` */
            INNER JOIN profesionales p ON s.id_servicio = p.id_servicio /* se junta con `profesionales` mediante 'id_servicio' */
            ORDER BY s.nombre ASC, p.apellido ASC, p.nombre ASC"; /* ordena por nombre de servicio, apellido y nombre del profesional de manera ascendente */

    $resultado = mysqli_query($conexion, $sql); // Y ejecuta la consulta
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../../css/mostrarservicios.css">
    <title>Document</title>
</head>
<body>
    <a href='../usuario/inicio.php'>Inicio</a><br>
    <?php
    // Verificar resultados
    if (mysqli_num_rows($resultado) > 0)
    {
        echo "<h1>Nuestras prestaciones</h1>";
        $servicios = []; // Array para almacenar servicios y sus profesionales

        // Mientras haya servicios sin procesar...
        while ($fila = mysqli_fetch_assoc($resultado))
        {
            $servicio = $fila['servicio']; // leera el nombre del servicio actual...
            if (!isset($servicios[$servicio])) // Y si el servicio no está en el array, lo agrega
            {
                $servicios[$servicio] = [
                'descripcion' => $fila['descripcion'],
                'profesionales' => [] ]; // Inicializa el array de profesionales...
            }
            $servicios[$servicio]['profesionales'][] = $fila['nombre_profesional'] . " " . $fila['apellido']; // Y agrega al profesional actual en el array de profesionales de X servicio
        }

        // Mostrar servicios y profesionales
        foreach ($servicios as $nombre_servicio => $datos)
        {
            echo "<h2>" . htmlspecialchars($nombre_servicio) . "</h2>";
            echo "<p><strong>Descripción:</strong> " . htmlspecialchars($datos['descripcion']) . "</p>";
            echo "<p><strong>Profesionales:</strong></p>";

            // Mostrar cada profesional del servicio actual
            foreach ($datos['profesionales'] as $prof)
            {
                echo "<li>" . htmlspecialchars($prof) . "</li>";
            }
            echo "<hr>";
        }
    }

    //Si no hay servicios registrados
    else
    {
        echo "<h3>No hay servicios registrados actualmente.</h3>";
    }
?>
</body>
</html>
