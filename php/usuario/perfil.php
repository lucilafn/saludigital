<?php
include('../conexion.php');
session_start();

//if (!isset($_SESSION['usuario'])) {
  //  echo'<script>
   // alert("Para poder ve su perfil debe iniciar sesión");
   // window.location.href = "form_iniciarsesion.php";
   // </script>';
//}

$sql = "SELECT * FROM usuarios"; 
$resultado = mysqli_query($conexion, $sql);

while ($arreglo = mysqli_fetch_assoc($resultado)) {
    echo "<p>Nombre y apellido: " . htmlspecialchars($arreglo['nombre']) . htmlspecialchars($arreglo['apellido']) . "</p>";
    echo "<p>DNI: " . htmlspecialchars($arreglo['dni']) . "</p>";
    echo "<p>Teléfono: " . htmlspecialchars($arreglo['telefono']) . "</p>";
    echo "<p>Correo electrónico: " . htmlspecialchars($arreglo['email']) . "</p>";
    if (($arreglo['obrasocial'])){
    echo "<p>Obra social: " . htmlspecialchars($arreglo['obrasocial']) . "</p>"; 
    }else{
    echo "No posee";
    }
  }

  echo "<h1>Mis turnos pendientes: </h1>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/perfil.css">
    <title>perfil</title>
</head>
<body>
    <header>
   <div class="header-content">
      <div class="profile-and-nav">
        <div class="profile">
          <img src="img/perfil.png" alt="Perfil">
          <div class="profile-name"><?php echo htmlspecialchars($_SESSION['usuario']); ?></div>
        </div>
        <nav>

        </nav>
      </div>
    </div>
  </header>
</body>
</html>