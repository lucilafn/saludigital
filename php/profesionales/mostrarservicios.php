<?php
    include('../conexion.php');
    session_start();
    
    // Consulta SQL: seleccionamos los datos de las tablas servicios y profesionales
    // Usamos INNER JOIN para relacionar ambas tablas por el campo id_servicio
    $sql = "SELECT s.nombre AS servicio, 
                   s.descripcion, 
                   p.nombre AS nombre_profesional, 
                   p.apellido
            FROM servicios s
            INNER JOIN profesionales p 
            ON s.id_servicio = p.id_servicio";
    
    $resultado = mysqli_query($conexion, $sql);
    echo "<h1>Nuestras prestaciones: </h1>";
    
    while ($arreglo = mysqli_fetch_assoc($resultado))
    {
        echo "<p><strong>Servicio:</strong> " . htmlspecialchars($arreglo['servicio']) . "</p>";
        echo "<p><strong>Profesional:</strong> " . htmlspecialchars($arreglo['nombre_profesional'] . " " . $arreglo['apellido']) . "</p>";
        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($arreglo['descripcion']) . "</p>";
        echo "<hr>";
    }
?>