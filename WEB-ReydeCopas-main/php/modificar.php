<?php
    $conexion = mysqli_connect("localhost", "root", "", "nutricionista") 
    or die('no se pudo conectar al servidor');

    $id = $_POST['id'];
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];

    $id = mysqli_real_escape_string($conexion, $id);
    $dia = mysqli_real_escape_string($conexion, $dia);
    $hora = mysqli_real_escape_string($conexion, $hora);

    $sql = "UPDATE turnos SET dia='$dia', hora='$hora' WHERE id =$id";

    mysqli_query($conexion, $sql);

    $stmt->close();
    $conexion->close();
?>