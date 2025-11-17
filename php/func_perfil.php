<?php
    $conexion = new mysqli("localhost", "root", "", "reyescopas");

    if (!isset($_SESSION['email'])) {
        header("Location: sesion.php");
        exit;
    }

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $email_usuario = $_SESSION['email'];

    $consulta = "SELECT u.nombre, u.apellido, u.email, u.dni, u.telefono, u.foto_perfil,
                        s.nro_socio, s.fecha_registro, s.estado, p.nombre_plan AS nombre_plan, p.costo_plan,
                        i.porcentaje AS interes
                 FROM usuarios u
                 LEFT JOIN socio s ON u.id = s.id_usua
                 LEFT JOIN planes p ON s.id_plan = p.id
                 LEFT JOIN interes i ON p.id_interes = i.id
                 WHERE u.email = ?";

    $stmt = $conexion->prepare($consulta);

    if ($stmt === false) {
        die("Error: No se pudo preparar la consulta. " . $conexion->error);
    }

    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    // 1. Inicialización de Variables
    $nombre = ""; $apellido = ""; $email = ""; $dni = ""; $telefono = "";
    $foto_perfil = null;
    $nro_socio = "N/A"; $fecha_registro = "N/A";
    $nombre_plan = "N/A"; $costo = "N/A";
    $interes = ""; // Nueva variable para el nombre del interés
    $estado = "";
    $nombreusuario = "Invitado";

    if($resultado->num_rows > 0)
    {
        $filas = $resultado->fetch_assoc();

        // Asignación de datos personales
        $nombre = $filas['nombre'];
        $apellido = $filas['apellido'];
        $email = $filas['email'];
        $dni = $filas['dni'];
        $telefono = $filas['telefono'];
        $foto_perfil = $filas['foto_perfil'];

        // Asignación de datos de membresía (con chequeo de NULL si el usuario no es socio)
        $nro_socio = $filas['nro_socio'] ?? "N/A";
        $fecha_registro = $filas['fecha_registro'] ?? "N/A";
        $nombre_plan = $filas['nombre_plan'] ?? "N/A";
        $costo = $filas['costo'] ?? "N/A";

        // Formatear el interés como porcentaje
        $interes_decimal = $filas['interes'] ?? null;
        if ($interes_decimal !== null) {
            // Convierte a float para eliminar ceros innecesarios y añade el símbolo %
            $interes = (float)$interes_decimal . '%';
        }
        $estado = $filas['estado'] ?? "N/A";


        $nombreusuario = $nombre . ' ' . $apellido;
    }

    $stmt->close();
    $conexion->close();
?>