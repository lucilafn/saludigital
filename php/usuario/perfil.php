<?php
  include('../conexion.php');
  session_start();

  if (!isset($_SESSION['usuario']))
  {
    header("Location: form_iniciosesion.php");
    exit();
  }

  echo "<a href='../usuario/inicio.php'><-- Volver</a>
        <a href='cerrarsesion.php'>Cerrar Sesion</a>
        <a href='../historialturnos.php'>Ver historial de turnos</a>";


  $id = $_SESSION['idusuario'];
  $sql = "SELECT * FROM usuarios WHERE id_usuario ='".$id."'";
  $resultado = mysqli_query($conexion, $sql);

  while ($arreglo = mysqli_fetch_assoc($resultado))
  {
    echo "<p>Nombre y apellido: " . htmlspecialchars($arreglo['nombre']) ." ". htmlspecialchars($arreglo['apellido']) . "</p>";
    echo "<p>DNI: " . htmlspecialchars($arreglo['dni']) . "</p>";
    echo "<p>Teléfono: " . htmlspecialchars($arreglo['telefono']) . "</p>";
    echo "<p>Correo electrónico: " . htmlspecialchars($arreglo['email']) . "</p>";

    if (($arreglo['obra_social']))
    {
      echo "<p>Obra social: " . htmlspecialchars($arreglo['obra_social']) . "</p>"; 
    }

    else
    {
      echo "<p>Obra social: No Posee </p>";
    }

    echo "<form action = 'modificarusuario.php' method = 'POST'>
          <input type = 'submit' value = 'modificar'>
          </form>";
  }

  echo htmlspecialchars($_SESSION['usuario']);
  echo "<h1>Mis turnos pendientes: </h1>";

  if (isset($_POST['id_turno'])) {
    $id_turno = ($_POST['id_turno']); 

    $sql = "SELECT * FROM turnos WHERE id_turno = $id_turno";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        // Si existe el turno, mostramos los botones
        echo "<form action='../modificarturno.php' method='POST'>
                <input type='hidden' name='id_turno' value='$id_turno'>
                <input type='submit' value='Modificar'>
              </form>";

        echo "<form action='../eliminarturno.php' method='POST'>
                <input type='hidden' name='id_turno' value='$id_turno'>
                <input type='submit' value='Eliminar'>
              </form>";
    } else {
        echo "Turno no encontrado.";
    }
} else {
    echo "No se reservo turno.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calendario Turnos</title>
  <!-- Estilos de FullCalendar (desde CDN, solo anda si tenes internet) -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
</head>
<body>
  <h2>Reservados</h2>
  <div id="calendar"></div>

  <!-- Librería FullCalendar (desde CDN, solo anda si tenes internet) -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <!-- Nuestro script para configurar el calendario -->
  <script src="js/calendario.js"></script>
</body>
</html>
