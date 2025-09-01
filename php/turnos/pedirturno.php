<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    echo'<script>
    alert("Para poder ve su perfil debe iniciar sesión");
    window.location.href = "form_iniciarsesion.php";
    </script>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedir Turno</title>
</head>
<body>
    <h2>Pedí tu turno</h2>
        <form action="buscarturnos.php" method="post">
            <!-- Lista de servicios -->
            <label>¿Qué servicio requerís?</label>
            <select name="idservicio" required>
            <option value="">Seleccionar servicio</option>
            <?php
                $sql = "SELECT * FROM servicios"; 
                $resultado = mysqli_query($conexion, $sql);
                while($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<option value='".$fila['id']."'>".$fila['nombre']."</option>";
                }
                ?>
            </select>

            <!-- Lista de médicos -->
            <label>¿Con qué profesional querés atenderte?</label>
            <select name="idprofesional" required>
                <option value="">Seleccionar profesional</option>
                <?php
                $sql = "SELECT * FROM medicos";
                $resultado = mysqli_query($conexion, $sql);
                while($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<option value='".$fila['id']."'>".$fila['nombre']." - ".$fila['servicio']."</option>";
                }
                ?>
            </select>

            <button type="submit">Buscar turnos disponibles</button>
        </form>
    </div>
</body>
</html>