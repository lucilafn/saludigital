<?php
require ('../conexion.php');

/*borrar datos por el id*/
if (isset($_POST['idproducto'])) {
    $id = $_POST['idproducto'];
    $sql = "DELETE FROM productos WHERE idproducto = '".$id."'";
    $resultado = mysqli_query($conexion, $sql);
    if ($resultado){
    //header("Location: administrador.php");
    }else{
    echo "algo salio mal";
}
}
    $sql = "SELECT * FROM productos";
         if ($result = $conexion->query($sql)){
            while($fila = $result->fetch_assoc()){ 
            echo "<p>".$fila['nombre']."</p>
            
            <br>
            <form action='' method='POST'>
            <input hidden type ='number' name='idproducto' value='".$fila['idproducto']."'>
            <input type = submit value='Eliminar producto'>
            <hr>
            </form>";
            }
        }  
?>