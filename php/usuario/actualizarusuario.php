<?php
    require_once('../conexion.php');
    session_start();

    if (!isset($_SESSION['usuario']))
    {
        header("Location: form_iniciosesion.php");
        exit();
    }

    if ($_SESSION['idusuario'])
    {
        // Validar
        $id = $_SESSION['idusuario'];
        $nombre = mysqli_real_escape_string ($conexion,$_POST['nombre']);
        $apellido = mysqli_real_escape_string ($conexion,$_POST['apellido']);
        $dni = mysqli_real_escape_string ($conexion,$_POST['dni']);
        $telefono= mysqli_real_escape_string ($conexion,$_POST['telefono']);
        $obrasocial = mysqli_real_escape_string ($conexion,$_POST['obra_social']);
        $correo = mysqli_real_escape_string ($conexion,$_POST['email']);
        $idusuario = $_SESSION['idusuario'];

        $sql = "UPDATE usuarios SET
        nombre = '".$nombre."',
        apellido = '".$apellido."',
        dni = '".$dni."',
        telefono = '".$telefono."',
        obra_social = '".$obrasocial."',
        email = '".$correo."'
        WHERE id_usuario = '".$id."'";

        $resultado = mysqli_query($conexion, $sql);

        if ($resultado)
        {
            header("Location: perfil.php");
        }
        
        else
        {
            echo "algo salio mal";
        }
    }
?>