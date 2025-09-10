  //aca consultamos el estado del turno, en caso de estar reservado (true) no imprimimos el boton y dejamos su fondo en rojo

if ($array["estado"] ) {
            //reservado
            echo "<td id='fondo_reserva_reservado'>Turno Reservado</td>";
        } else {
            //no reservado
            echo "<td id='fondo_reserva_disponible'><form action='reservar.php' method='post'>
                        <input hidden type='number' name='id_turno' value='".$array["id_turno"]."'>
                        <input type='submit' value='Reservar'>
                </form></td>";
        }