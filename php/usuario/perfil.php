<?php
include('../conexion.php');
session_start();
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
          <a href="historial.php" class="back-button">Historial de compras</a>
          <a href="../producto/misproduct.php" class="back-button">Mis productos </a>
          <a href="misventas.php" class="back-button">Mis ventas</a>
          <a href="\spaceshop\index.php" class="back-button">Menu</a>
          <a href="cerrarsesion.php" class="back-button">Cerrar sesion</a>
        </nav>
      </div>
    </div>
  </header>
</body>
</html>