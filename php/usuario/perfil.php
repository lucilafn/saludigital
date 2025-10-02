<?php
  include('../conexion.php');
  session_start();

  if (!isset($_SESSION['usuario'])) {
    header("Location: form_iniciosesion.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../../css/perfil.css">
  <title>Calendario Turnos</title>
  <!-- Estilos de FullCalendar (desde CDN, solo anda si tenes internet) -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
</head>
<body>

 <body>
  <nav>
    <a href="../usuario/inicio.php"><-- Volver</a>
    <a href="cerrarsesion.php">Cerrar Sesión</a>
    <a href="/saludigital/php/turnos/historialturnos.php">Ver historial de turnos</a>
  </nav>

  <div class="layout">
    <!-- Perfil -->
    <div class="user-info">
      <h2>Mis Datos</h2>
      <?php
        $id = $_SESSION['idusuario'];
        $sql = "SELECT * FROM usuarios WHERE id_usuario ='".$id."'";
        $resultado = mysqli_query($conexion, $sql);

        while ($arreglo = mysqli_fetch_assoc($resultado)) {
          echo "<p><strong>Nombre y apellido:</strong> " . htmlspecialchars($arreglo['nombre']) ." ". htmlspecialchars($arreglo['apellido']) . "</p>";
          echo "<p><strong>DNI:</strong> " . htmlspecialchars($arreglo['dni']) . "</p>";
          echo "<p><strong>Teléfono:</strong> " . htmlspecialchars($arreglo['telefono']) . "</p>";
          echo "<p><strong>Correo electrónico:</strong> " . htmlspecialchars($arreglo['email']) . "</p>";

          if ($arreglo['obra_social']) {
            echo "<p><strong>Obra social:</strong> " . htmlspecialchars($arreglo['obra_social']) . "</p>"; 
          } else {
            echo "<p><strong>Obra social:</strong> No posee</p>";
          }

          echo "<form action='modificarusuario.php' method='POST'>
                  <input type='submit' value='Modificar'>
                </form>";
        }
      ?>
    </div>

    <!-- Calendario -->
    <div>
      <h2>Reservados</h2>
      <div id="calendar"></div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="/saludigital/js/calendario.js"></script>
</body>


</body>
</html>
