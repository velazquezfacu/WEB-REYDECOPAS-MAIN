<?php
    session_start();
    header('Content-Type: application/json');

    $conexion = new mysqli("localhost", "root", "", "reyescopas");

    if ($conexion->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        exit();
    }

    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $mail = $_POST['email'] ?? '';
    $contrasena_plain = $_POST['contrasena'] ?? '';
    $dni = $_POST['dni'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    // Validación del servidor
    if (empty($nombre) || empty($apellido) || empty($mail) || empty($contrasena_plain) || empty($dni) || empty($telefono)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }

    if (!is_numeric($telefono) || strlen($telefono) !== 10) {
        echo json_encode(['success' => false, 'message' => 'El número de teléfono debe contener exactamente 10 dígitos.']);
        exit();
    }

    // Verificar si el DNI o el email ya existen usando consultas preparadas
    $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE dni = ? OR email = ?");
    $stmt_check->bind_param("ss", $dni, $mail);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El DNI o el email ya están registrados.']);
        $stmt_check->close();
        $conexion->close();
        exit();
    }
    $stmt_check->close();

    // Insertar nuevo usuario
    $contrasena_hashed = password_hash($contrasena_plain, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO usuarios(nombre, apellido, email, contrasena, dni, telefono) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("ssssss", $nombre, $apellido, $mail, $contrasena_hashed, $dni, $telefono);

    if ($stmt_insert->execute()) {
        // Iniciar sesión automáticamente
        $_SESSION['user_id'] = $stmt_insert->insert_id;
        $_SESSION['email'] = $mail;
        $_SESSION['nombre'] = $nombre;
        echo json_encode(['success' => true, 'redirect' => 'perfil.php']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
    }
    $stmt_insert->close();
    $conexion->close();
?>