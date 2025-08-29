<?php
include_once('../conexion.php');

if (isset($_POST['registrar'])) {
//inyecciones sql
    $nombre = mysqli_real_escape_string ($conexion, $_POST['nombre']);
    $email = mysqli_real_escape_string ($conexion, $_POST['email']);
    $apellido = mysqli_real_escape_string ($conexion, $_POST['direccion']);
    $dni = mysqli_real_escape_string ($conexion, $_POST['telefono']);
    $contrasenia = mysqli_real_escape_string ($conexion, $_POST['contrasenia']);
    $telefono = mysqli_real_escape_string ($conexion, $_POST['email']); 
    $obrasocial = mysqli_real_escape_string ($conexion, $_POST['email']);

    //consulta de sql filtrando por correo
    $consultausuario="SELECT * FROM usuarios WHERE email ='".$email."'";
    $resultado=mysqli_query($conexion,$consultausuario);

    if (mysqli_num_rows($resultado)>0) {
         // Usuario ya existe
            echo '<script>
                alert("Usuario ya registrado. Por favor, inicie sesión.");
                window.location.href = "form_iniciosesion.php";
            </script>';
    } else {
        //si el usuario no existe
        $contra_encriptada = md5($contrasenia);
        $insertar_usuario= "INSERT INTO `usuarios`(`nombre`, `apellido`,
        `dni`, `telefono`, `obra_social`, `email`, `contrasenia`, `administrador`) VALUES (
        '".$nombre."',
        '".$apellido."',
        '".$dni."',
        '".$telefono."',
        '".$obrasocial."',
        '".$email."',
        '".$contra_encriptada."',
        '0')"; 

        $resultado_insercion = mysqli_query($conexion,$insertar_usuario);
        //intentamos insertar
        if ($resultado_insercion) {
        //se inserto correctamente
        echo '<script>
                alert("Registro exitoso. Ahora puedes iniciar sesión.");
                window.location.href = "form_iniciosesion.php";
            </script>';
        }else {
        //no se inserto
        echo '<script>
                alert("Registro fallido. reintente registrarse.");
                window.location.href = "form_registro.php";
            </script>';
        }
    }
}        

?>
