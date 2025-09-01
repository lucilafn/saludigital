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

        <form action="buscarturnos.php" method="POST">
            <!-- Lista de servicios -->
            <label>¿Qué servicio requerís?</label>
            <select name="idservicio" required>
                <option value="">Seleccionar servicio</option>
                <?php
                $sql = "SELECT * FROM servicios";
                $resultado = mysqli_query($conexion, $sql);
                while($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<option value='".$fila['id_servicio']."'>".$fila['nombre']."</option>";
                }
                ?>
            </select>

            <!-- Lista de profesionales -->
            <label>¿Con qué profesional querés atenderte?</label>
            <select name="idprofesional" required>
                <option value="">Seleccionar profesional</option>
                <?php
                $sql = "SELECT p.id_profesional, p.nombre, p.apellido, s.nombre AS servicio
                        FROM profesionales p
                        INNER JOIN servicios s ON p.id_servicio = s.id_servicio";
                $resultado = mysqli_query($conexion, $sql);
                while($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<option value='".$fila['id_profesional']."'>".$fila['nombre']." ".$fila['apellido']." (".$fila['servicio'].")</option>";
                }
                ?>
            </select>

            <!-- Fecha -->
            <label>¿Qué día querés atenderte?</label>
            <input type="date" name="fecha" required min="<?php echo date('Y-m-d'); ?>">

            <button type="submit">Buscar turnos disponibles</button>
        </form>
    </div>
</body>
</html>