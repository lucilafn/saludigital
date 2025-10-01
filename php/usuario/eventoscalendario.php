<?php
// Indicamos que la respuesta será JSON
header('Content-Type: application/json');
include('../conexion.php');

// Consulta para traer todos los turnos con información del paciente, doctor y servicio
$sql = "SELECT 
            t.id_turno,
            h.dia,
            h.hora,
            p.nombre AS profesional_nombre,
            p.apellido AS profesional_apellido,
            s.nombre AS servicio
        FROM turnos t
        INNER JOIN horarios h ON t.id_horario = h.id_horario
        INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
        INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
        INNER JOIN servicios s ON p.id_servicio = s.id_servicio";

$result = $conexion->query($sql);

$eventos = [];

// Recorremos los resultados de la consulta
while ($row = $result->fetch_assoc()) {
    // Concatenamos la fecha y la hora en formato ISO para el calendario
    $fechaHora = $row['dia'] . " " . $row['hora'];

    // Armamos el título del evento
    $titulo = " - " . $row['profesional_nombre'] . " " . $row['profesional_apellido'] .
              " - " . date("H:i", strtotime($row['hora']));

    // Construimos el array que FullCalendar necesita
    $eventos[] = [
        'id' => $row['id_turno'],    // ID del turno
        'title' => $titulo,          // Texto que se verá en el calendario
        'start' => $fechaHora,       // Fecha y hora de inicio
        'extendedProps' => [         // Propiedades extras (se usan en el popup)
            'doctor' => $row['profesional_nombre'] . " " . $row['profesional_apellido'],
            'servicio' => $row['servicio']
        ]
    ];
}

// Devolvemos el JSON
echo json_encode($eventos);