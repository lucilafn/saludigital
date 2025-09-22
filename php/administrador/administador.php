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
            <tr>
                <?php 
                    if ($array["estado"]) //reservado
                    {
                       echo "<td id='fondo_reserva_reservado'>Turno Reservado</td>";
                    }
                      
                    else //no reservado
                    {
                       echo "<td id='fondo_reserva_disponible'>
                                <form action='reservar.php' method='post'>
                                    <input hidden type='number' name='id_turno' value='".$array["id_turno"]."'>
                                    <input type='submit' value='Reservar'>
                                </form>
                            </td>";
                   }
                ?>
            </tr>
        </table>
    </body>
</html>