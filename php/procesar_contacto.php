<?php
header('Content-Type: application/json');

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no válido.']);
    exit();
}

// Validar datos
if (!isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['mensaje'])) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit();
}

$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$mensaje = trim($_POST['mensaje']);

// Validaciones
if (empty($nombre) || empty($email) || empty($mensaje)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El email no es válido.']);
    exit();
}

if (strlen($nombre) < 3) {
    echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres.']);
    exit();
}

if (strlen($mensaje) < 10) {
    echo json_encode(['success' => false, 'message' => 'El mensaje debe tener al menos 10 caracteres.']);
    exit();
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "reyescopas");

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit();
}

// Insertar el contacto
$sql = "INSERT INTO contactos (nombre, email, mensaje, estado) VALUES (?, ?, ?, 'nuevo')";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    $conexion->close();
    exit();
}

$stmt->bind_param("sss", $nombre, $email, $mensaje);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => '¡Mensaje enviado exitosamente! Te contactaremos pronto.'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje. Intentá de nuevo.']);
}

$stmt->close();
$conexion->close();
?>
