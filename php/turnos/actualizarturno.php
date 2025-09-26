<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: ../usuario/form_iniciosesion.php");
    exit();
}
$id_usuario = ($_SESSION['idusuario']); 

//validar datos recibidos
if (!isset($_POST['id_turno']) || !isset($_POST['id_horario'])) {
    header("Location: mis_turnos.php");
    exit();
}

$id_turno = ($_POST['id_turno']);     // turno que se va a modificar
$id_horario = ($_POST['id_horario']); // nuevo horario elegido

//validar que el turno existe y es del usuario
$sql = "SELECT t.id_turno
        FROM turnos t
        WHERE t.id_turno = $id_turno AND t.id_usuario = $id_usuario";

$res = mysqli_query($conexion, $sql);

if (mysqli_num_rows($res) == 0) {
    die("Error: turno no encontrado o no pertenece al usuario.");
}

//validar que el nuevo horario esté disponible (no tenga turno asignado y sea futuro)
$sql_h = "SELECT h.id_horario
          FROM horarios h
          LEFT JOIN turnos t ON h.id_horario = t.id_horario
          WHERE h.id_horario = $id_horario
          AND (h.dia > CURDATE() OR (h.dia = CURDATE() AND h.hora > CURTIME()))
          AND t.id_turno IS NULL";

$res_h = mysqli_query($conexion, $sql_h);

if (mysqli_num_rows($res_h) == 0) {
    die("Error: el horario seleccionado no está disponible.");
}

// actualizar turno
$sql_update = "UPDATE turnos 
               SET id_horario = $id_horario 
               WHERE id_turno = $id_turno AND id_usuario = $id_usuario";

if (mysqli_query($conexion, $sql_update)) {
    echo "<script>
            alert('El turno se modificó correctamente.');
            window.location.href = '../perfil.php';
          </script>";
} else {
    echo "Error al modificar el turno: " . mysqli_error($conexion);
}
?>