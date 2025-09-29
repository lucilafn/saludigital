<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: ../usuario/form_iniciosesion.php");
    exit();
}
$id_usuario = ($_SESSION['idusuario']);

if (!isset($_GET['id_turno'])) {
    header("Location: ../perfil.php");
    exit();
}
$id_turno = ($_GET['id_turno']);

// Validar que el turno sea del usuario logueado
$sql = "SELECT id_turno FROM turnos 
        WHERE id_turno = $id_turno AND id_usuario = $id_usuario";

$res = mysqli_query($conexion, $sql);

if (mysqli_num_rows($res) == 0) {
    die("Error: turno no encontrado o no pertenece al usuario.");
}

//eliminano
$sql_elim = "DELETE FROM turnos 
            WHERE id_turno = $id_turno AND id_usuario = $id_usuario";

if (mysqli_query($conexion, $sql_elim)) {
    echo "<script>
            alert('El turno fue eliminado correctamente.');
            window.location.href = '../perfil.php';
          </script>";
} else {
    echo "Error al eliminar turno: " . mysqli_error($conexion);
}
?>
