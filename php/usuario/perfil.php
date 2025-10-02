<?php
  include('../conexion.php');
  session_start();

  if (!isset($_SESSION['usuario'])) {
    header("Location: form_iniciosesion.php");
    exit();
  }

  echo "<a href='inicio.php'><-- Volver</a>
        <a href='cerrarsesion.php'>Cerrar Sesión</a>
        <a href='../turnos/historialturnos.php'>Ver historial de turnos</a>";

  $id = $_SESSION['idusuario'];
  $sql = "SELECT * FROM usuarios WHERE id_usuario ='".$id."'";
  $resultado = mysqli_query($conexion, $sql);

  while ($arreglo = mysqli_fetch_assoc($resultado)) {
    echo "<p>Nombre y apellido: " . htmlspecialchars($arreglo['nombre']) ." ". htmlspecialchars($arreglo['apellido']) . "</p>";
    echo "<p>DNI: " . htmlspecialchars($arreglo['dni']) . "</p>";
    echo "<p>Teléfono: " . htmlspecialchars($arreglo['telefono']) . "</p>";
    echo "<p>Correo electrónico: " . htmlspecialchars($arreglo['email']) . "</p>";

    if (($arreglo['obra_social'])) {
      echo "<p>Obra social: " . htmlspecialchars($arreglo['obra_social']) . "</p>"; 
    } else {
      echo "<p>Obra social: No Posee </p>";
    }

    echo "<form action='modificarusuario.php' method='POST'>
            <input type='submit' value='Modificar'>
          </form>";
  }

  echo "<h1>Mis turnos pendientes:</h1>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calendario Turnos</title>
  <!-- Estilos de FullCalendar -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
  <style>
    #calendar {
      max-width: 900px;
      margin: 20px auto;
    }
  </style>
</head>
<body>
  <h2>Calendario de Turnos</h2>
  <div id="calendar"></div>

  <!-- api de sweetalert -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- Librería FullCalendar -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <!-- Nuestro script del calendario -->
  <script src="/saludigital/saludigital/js/calendario.js"></script>
</body>
</html>
