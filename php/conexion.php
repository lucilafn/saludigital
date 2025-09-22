<?php
    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $basedatos = "saludigital";
    $conexion = mysqli_connect($servidor, $usuario, $password, $basedatos);
    
    if(!$conexion)
    {
        die("ERROR 404: ". mysqli_connect_error());
    } 
?>