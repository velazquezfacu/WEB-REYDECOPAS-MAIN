<?php
    session_start();
    $conexion = new mysqli("localhost", "root", "", "reyescopas");

    if (!isset($_SESSION['email'])) {
        die("Error: Usuario no logueado");
    }

    $email_usuario = $_SESSION['email'];

    $consulta = "SELECT nombre, apellido FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt === false) {
        die("Error: No se pudo preparar la consulta. "  . $conexion->error);
    }

    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $nombreusuario = "Invitado";

    if($resultado->num_rows > 0)
      {
        $filas = $resultado->fetch_assoc();
        $nombreusuario = $filas['nombre'] . ' ' . $filas['apellido'];
      
      }

      $stmt->close();
      $conexion->close();

?>