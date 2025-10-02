<?php
include('../conexion.php');
session_start();
// Traer todos los turnos con información del doctor, servicio y horario
$sql = "
    SELECT 
        t.id_usuario,
        t.id_turno,
        p.nombre AS doctor_nombre,
        p.apellido AS doctor_apellido,
        s.nombre AS servicio,
        h.dia,
        h.hora
    FROM turnos t
    INNER JOIN horarios h ON t.id_horario = h.id_horario
    INNER JOIN profesionales p ON h.id_profesional = p.id_profesional
    INNER JOIN servicios s ON p.id_servicio = s.id_servicio
    WHERE t.id_usuario = '".$_SESSION['idusuario']."';
";

$resultado = mysqli_query($conexion, $sql);

$events = [];
// Recorremos los resultados de la consulta
while ($row = mysqli_fetch_assoc($resultado)) {
    $id = $row['id_turno'];
    $doctor = $row['doctor_nombre'] . " " . $row['doctor_apellido'];
    $servicio = $row['servicio'];
    $fechaHora = $row['dia'] . " " . $row['hora'];
// Construimos el array que FullCalendar necesita
    $events[] = [
        "id" => $id,
        "title" => "$doctor", // título breve
        "start" => $fechaHora,
        "extendedProps" => [
            "doctor" => $doctor,
            "servicio" => $servicio,
            "hora" => $row['hora']
        ]
    ];
}
// Indicamos que la respuesta será JSON
header('Content-Type: application/json');
echo json_encode($events);
?>
