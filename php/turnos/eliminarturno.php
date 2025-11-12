<?php
include('../conexion.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_turno = $_POST['id'];
    
    $sql = "DELETE FROM turnos WHERE id_turno = ? AND id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_turno, $_SESSION['idusuario']);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true, "message" => "Turno eliminado correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar el turno"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud inválida"]);
}
?>