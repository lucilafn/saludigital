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
    <title>Inicio</title>
  </head>
  <body>
    <header>
      <div class="logo">
        <img src="img/logo.png" alt="Logo Consultorio" width="35">
        <span>SaluDigital</span>
      </div>
      <nav>
        <div class="topnav">
          <div id="myLinks">
            <?php if (!isset($_SESSION['usuario'])): ?>
              <a href="form_iniciosesion.php"> Iniciar sesión</a>
              <a href="form_registro.php">Registrarse</a>
            <?php else: ?>
              <a href="perfil.php"> Mi Perfil</a>
              <a href="../turnos/pedirturno.php"> Turnos</a>
              <a href="cerrarsesion.php">Cerrar sesión</a>
            <?php endif; ?>
          </div>
          <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>☰
          </a>
          <script>
            function toggleMenu() {
            document.getElementById("nav").classList.toggle("show");
s</script>
        </div>
      </nav>
    </header>
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
  </body>
</html>