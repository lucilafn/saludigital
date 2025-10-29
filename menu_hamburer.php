<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/x-icon" href="img/Logo-Médico-con-Estetoscopio.ico">
<style>

/* estilo menu movil */
body {
  font-family: Arial, Helvetica, sans-serif;
}

.mobile-container {
  background-color: #555;
  color: white;
}

.topnav {
  overflow: hidden;
  background-color: #333;
  position: relative;
}

.topnav #myLinks {
  display: none;
}

.topnav a {
  color: white;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  display: block;
}

.topnav a.icon {
  background: black;
  display: block;
  position: absolute;
  right: 0;
  top: 0;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.active {
  background-color: #04AA6D;
  color: white;
}

.topnav img {
  height: 40px;
  padding: 5px 10px;
}

</style>
</head>
<body>

<!-- Simulate a smartphone / tablet -->
<div class="mobile-container">

<!-- Top Navigation Menu -->
<div class="topnav">
  <img src="img/logo.jpg" alt="Logo Saludigital">
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


<script>
function myFunction() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
    console.log(x.style.display);
  } else {
    x.style.display = "block";
    console.log(x.style.display);
  }
}
</script>

</body>
</html>
