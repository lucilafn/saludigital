<?php
  // Incluir el archivo de conexión a la base de datos
  require_once('../conexion.php');

  // Inicia la sesión
  session_start();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Inicio</title>
  </head>
  <body>
    <header>
      <div class="logo">
      <img src="../../img/logosdl.png" alt="">
        <span>SaluDigital</span>
      </div>
      <nav class="topnav">
    <div id="myLinks">
      <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="form_inicioSesion.php">Iniciar sesión</a>
        <a href="form_registro.php">Registrarse</a>
      <?php else: ?>
        <a href="perfil.php">Mi Perfil</a>
        <a href="../turnos/pedirturno.php">Turnos</a>
        <a href="cerrarsesion.php">Cerrar sesión</a>
      <?php endif; ?>
    </div>
  </nav>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
      <i class="fa fa-bars"></i>
    </a>
    </header>
    <script>
function myFunction() {
  document.getElementById("myLinks").classList.toggle("show");
}
</script>
    <!-- ===== SECCIÓN BIENVENIDA ===== -->
    <div class="seccion">
      <h1>Bienvenido</h1>
    </div>

    <!-- ===== IMAGEN DEL CONSULTORIO ===== -->
    <div class="imagen-consultorio">
      Imagen del consultorio
    </div>

    <!-- ===== INFORMACIÓN ===== -->
    <section class="section-info">
      <h2>Ubicación</h2>
      <p>Calle y altura del consultorio</p>
    </section>

    <section class="section-info">
      <h2>Descripción</h2>
      <p>Descripción del consultorio.</p>
    </section>

    <section class="section-info">
      <h2>Prestaciones</h2>
      <p>Prestaciones del consultorio.</p>
    </section>

    <section class="section-info">
      <h2>Contacto</h2>
      <p>Teléfono, email, horarios de atención.</p>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer>
      <p>Información de nuestro emprendimiento</p>
    </footer>
    <script>
function myFunction() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
</script>
  </body>
</html>