<?php
include('conexion.php');

//recibimos por el formulario el id del turno
$id_turno = $_POST['id'];

//modificamos el estado del turno y redirigimos
$sql = "UPDATE turnos SET reservado='1' WHERE id='".$id_turno."'";
$resultado = mysqli_query($conexion, $sql);

header("Location: index.php");
?>