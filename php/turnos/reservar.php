<?php
include('../conexion.php');

//recibimos por el formulario el id del turno
$id_turno = $_POST['id_turno'];

//modificamos el estado del turno y redirigimos
$sql = "UPDATE turnos SET estado ='1' WHERE id_turno='".$id_turno."'";
$resultado = mysqli_query($conexion, $sql);

header("Location: ../usuario/inicio.php");
?>