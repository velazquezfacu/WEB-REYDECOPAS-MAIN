<?php
   $conn = new mysqli("localhost", "root", "", "reyescopas");
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=? WHERE dni=?";
    $stmt = $conn->prepare($sql)

    if ($stmt === false) {
    // Si hay un error en la sintaxis SQL, muestra el error
    die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $dni);

    if ($stmt->execute()) {
    // Éxito: Redirigir al usuario al perfil o a una página de confirmación
    header("Location: perfil.php?status=success");
    } else {
    // Fallo: Mostrar un error (o enviar un JSON si es AJAX)
    header("Location: perfil.php?status=error&msg=" . urlencode($conn->error));
    }


    $stmt->close();
    $conn->close();
?>