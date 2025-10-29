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

    <!-- Si no hay una sesion existente... -->
    <!-- Si no hay una sesion existente... -->
<?php if (!isset($_SESSION['usuario'])): ?>
  <header>
    <div class="logo">
      <img src="img/logo.png" alt="Logo Consultorio" width="35">
      <span>SaluDigital</span>
    </div>
    <nav>
      <div class="acciones">
        <a href="form_iniciosesion.php" class="btn-acceso">
          <img src="../../img/login.png" alt="login"> Iniciar sesión
        </a>
        <a href="form_registro.php" class="btn-acceso">
          <img src="../../img/register.png" alt="registro"> Registrarse
        </a>
      </div>
    </nav>
  </header>

  <div class="seccion">
      <h1>Bienvenido</h1>
  </div>
<?php endif; ?>

    <!-- Si hay una sesion existente... -->
    <?php
    if (isset($_SESSION['usuario'])):
?>
<header>
    <div class="logo">
      <img src="img/logo.png" alt="Logo Consultorio" width="35">
      <span>SaluDigital</span>
    </div>
    <nav>
        <div class="acciones">
          <?php if (!isset($_SESSION['usuario'])): ?>
              <a href="form_iniciosesion.php"><img src="../../img/login.png"> Iniciar sesión</a>
              <a href="form_registro.php"><img src="../../img/register.png"> Registrarse</a>
          <?php else: ?>
              <a href="perfil.php"><img src="../../img/profile.png"> Mi Perfil</a>
              <a href="../turnos/pedirturno.php"><img src="../../img/calendar.png"> Turnos</a>
              <a href="cerrarsesion.php"><img src="../../img/logout.png"> Cerrar sesión</a>
          <?php endif; ?>
      </div>
    </nav>
    <!-- Top Navigation Menu -->
  <div class="topnav">
    <div id="myLinks">
      <a href="index.html" class="active">Inicio</a>
      <a href="php/nosotros/servicios.html">Servicios</a>
      <a href="php/nosotros/nosotros.html">Nosotros</a>
      <a href="php/nosotros/contacto.html">Contacto</a>
    </div>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
  </div>
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
<?php endif; ?>
</body>
</html>