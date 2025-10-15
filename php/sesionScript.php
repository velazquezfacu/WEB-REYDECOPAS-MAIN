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

// 3. Consulta ÚNICA y SEGURA
$sql = "SELECT id, nombre, contrasena FROM usuarios WHERE email = ?";
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
    $stmt->bind_result($id, $nombre, $hashed_password);
    $stmt->fetch();

    // 4. Verificación de Contraseña
    if (password_verify($contrasena, $hashed_password)) {
        // INICIO DE SESIÓN EXITOSO
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $mail;
        $_SESSION['nombre'] = $nombre;

        $stmt->close();
        $conexion->close();

        // Enviar la respuesta JSON que el AJAX espera
        echo json_encode(['success' => true, 'redirect' => 'altasocio.php']);
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