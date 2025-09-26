<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/administrador.css">
        <title>Administrador</title>
    </head>
    <body>
        <table>
            <th>Turnos</th>
            <th>Usuario</th>
            <th>Dia</th>
            <th>Horarios</th>
            <tr>
                <?php
                    include("../conexion.php");
                    $sql = "SELECT * FROM turnos INNER JOIN usuarios ON turnos.id_usuario = usuarios.id_usuario
                                                 INNER JOIN horarios ON turnos.id_horario = horarios.id_horario";
                    $resultado = mysqli_query($conexion,$sql);

                    if (mysqli_num_rows($resultado)>0) {
                        //hay turnos
                        while ($arreglo = mysqli_fetch_assoc($resultado)) {
                            echo "<td>".$arreglo['id_turno']."</td>";
                            echo "<td>".$arreglo['nombre']."</td>";
                            echo "<td>".$arreglo['dia']."</td>";
                            echo "<td>".$arreglo['hora']."</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "sin turnos registrados";
                    } 
                ?>
        </table>
    </body>
</html>
