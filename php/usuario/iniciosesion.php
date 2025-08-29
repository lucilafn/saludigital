<?php
include('../conexion.php');
session_start();

if (isset($_SESSION['usuario'])) {
    echo'<script>
       alert("Para ingresar a esta pagina, no debe tener una sesion activa");
       window.location.href = "/saludigital/inicio.php";
       </script>';
}

if(!isset($_POST['email'])){ //VERIFICACION DE SESION Y ENTRADA POR URL
   header('Location: /saludigital/inicio.php');
}
else
{
    $email = $_POST['email'];
    $contrasenia = $_POST['contrasenia'];
    $contra_encriptada = md5($contrasenia); //ENCRIPTACION DE CONTRASEÑA DE FORMULARIO
    $sql="SELECT * FROM usuarios where email = '".$email."'"; //CONSULTAMOS SI EXISTE EL USUARIO
    $resultado = mysqli_query($conexion,$sql);

    if(mysqli_num_rows($resultado)>0)
    { //SI EXISTE LA CUENTA
        $datos=mysqli_fetch_assoc($resultado);
        if ($contra_encriptada==$datos['contrasenia'])
        { //SI LAS CONTRASEÑAS COINCIDEN
         $_SESSION['usuario']=$datos['nombre']; //INICIAMOS SESION
         $_SESSION['idusuario'] = $datos['idusuario'];
         $_SESSION['administrador']=$datos['administrador'];
         $_SESSION['email'] = $datos['email'];
        //verificar si es admin
            if ($_SESSION['administrador'] == 1) {
            header("Location: ../administrador/administrador.php");
            exit();
        } else {
           header("Location: /saludigital/inicio.php");
            exit();
        }
        }
        else
        {
            //si las contraseñas no coinciden
            echo'<script>
            alert("contraseña incorrecta, por favor intente de nuevo");
              window.location.href = "form_iniciosesion.php";
            </script>';
        }
    }
    else {
        //no existe la cuenta
        echo'<script>
            alert("Usuario ingresado no existe, por favor, verifique la cuenta o registrese ");
           window.location.href = "form_registro.php";
            </script>';
    }
}
?>

