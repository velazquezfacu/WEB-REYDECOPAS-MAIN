<?php
session_start();

// Establecer la cabecera JSON al principio para todas las respuestas.
header('Content-Type: application/json');

// 1. Manejo de error: Solo procesar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
    exit();
}

// 2. Manejo de error: Verificar que las claves POST existan
if (!isset($_POST['email']) || !isset($_POST['contrasena'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos de inicio de sesión.']);
    exit();
}


$mail = $_POST['email'];
$contrasena = $_POST['contrasena'];

$conexion = new mysqli("localhost", "root", "", "reyescopas");

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit();
}

// 3. Consulta ÚNICA y SEGURA - Incluir estado del socio si existe
$sql = "SELECT u.id, u.nombre, u.email, u.contrasena, s.estado
        FROM usuarios u
        LEFT JOIN socio s ON u.id = s.id_usua
        WHERE u.email = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    // Manejo de error en la preparación de la consulta
    echo json_encode(['success' => false, 'message' => 'Error interno del sistema.']);
    $conexion->close();
    exit();
}

$stmt->bind_param("s", $mail);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nombre, $email_db, $hashed_password, $estado_socio);
    $stmt->fetch();

    // 4. Verificación de Contraseña
    if (password_verify($contrasena, $hashed_password)) {

        // Verificar si el usuario es un socio inactivo
        if ($estado_socio !== null && $estado_socio === 'Inactivo') {
            $stmt->close();
            $conexion->close();

            // Devolver respuesta especial para socio inactivo
            echo json_encode([
                'success' => false,
                'inactive' => true,
                'message' => 'Tu cuenta está dada de baja.',
                'user_name' => $nombre,
                'user_email' => $email_db
            ]);
            exit();
        }

        // INICIO DE SESIÓN EXITOSO
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $mail;
        $_SESSION['nombre'] = $nombre;

        // Determina la URL de redirección.
        if ($nombre === 'super') {
            $redirect_url = 'admin.php';
        } else {
            // Si se envía 'redirect_url' desde el formulario, se usará ese valor, si no, 'perfil.php'.
            $redirect_url = isset($_POST['redirect_url']) && !empty($_POST['redirect_url']) ? $_POST['redirect_url'] : 'perfil.php';
        }

        $stmt->close();
        $conexion->close();

        // Enviar la respuesta JSON que el AJAX espera
        echo json_encode(['success' => true, 'redirect' => $redirect_url]);
        exit();

    } else {
        // Contraseña incorrecta
        echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos.']);
    }
} else {
    // Usuario no encontrado
    echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos.']);
}

$stmt->close();
$conexion->close();
exit();
?>