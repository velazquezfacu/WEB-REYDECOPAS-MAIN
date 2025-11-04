<?php
    $conexion = mysqli_connect("localhost", "root", "", "reyescopas") 
    or die('no se pudo conectar al servidor');

    $id = $_POST['id'];

    $id = mysqli_real_escape_string($conexion, $id);

    $sql = "DELETE FROM turnos WHERE id =$id";

    mysqli_query($conexion, $sql);

    mysqli_close($conexion);
?>