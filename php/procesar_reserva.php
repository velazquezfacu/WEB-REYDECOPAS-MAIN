<?php
session_start();
header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para reservar.']);
    exit();
}

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no válido.']);
    exit();
}

// Validar datos
if (!isset($_POST['id_experiencia']) || !isset($_POST['fecha_experiencia'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
    exit();
}

$id_experiencia = intval($_POST['id_experiencia']);
$id_usuario = $_SESSION['user_id'];
$fecha_experiencia = $_POST['fecha_experiencia'];

// Validar que la fecha sea futura
if (strtotime($fecha_experiencia) <= strtotime('today')) {
    echo json_encode(['success' => false, 'message' => 'La fecha debe ser posterior a hoy.']);
    exit();
}

$conexion = new mysqli("localhost", "root", "", "reyescopas");

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit();
}

// Obtener información de la experiencia
$sql_experiencia = "SELECT cupo_max FROM experiencias WHERE id = ?";
$stmt = $conexion->prepare($sql_experiencia);
$stmt->bind_param("i", $id_experiencia);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Experiencia no encontrada.']);
    $stmt->close();
    $conexion->close();
    exit();
}

$experiencia = $resultado->fetch_assoc();
$cupo_max = $experiencia['cupo_max'];
$stmt->close();

// Verificar cupo disponible para esa fecha
$sql_count = "SELECT COUNT(*) as total FROM reservas_experiencias
              WHERE id_experiencia = ? AND fecha_experiencia = ? AND estado != 'cancelada'";
$stmt = $conexion->prepare($sql_count);
$stmt->bind_param("is", $id_experiencia, $fecha_experiencia);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();
$reservas_actuales = $row['total'];
$stmt->close();

if ($reservas_actuales >= $cupo_max) {
    echo json_encode(['success' => false, 'message' => 'No hay cupos disponibles para esta fecha.']);
    $conexion->close();
    exit();
}

// Verificar si el usuario ya tiene una reserva para esta experiencia en esta fecha
$sql_check = "SELECT id FROM reservas_experiencias
              WHERE id_usuario = ? AND id_experiencia = ? AND fecha_experiencia = ? AND estado != 'cancelada'";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("iis", $id_usuario, $id_experiencia, $fecha_experiencia);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Ya tienes una reserva para esta experiencia en esta fecha.']);
    $stmt->close();
    $conexion->close();
    exit();
}
$stmt->close();

// Crear la reserva
$sql_insert = "INSERT INTO reservas_experiencias (id_experiencia, id_usuario, fecha_experiencia, estado)
               VALUES (?, ?, ?, 'confirmada')";
$stmt = $conexion->prepare($sql_insert);
$stmt->bind_param("iis", $id_experiencia, $id_usuario, $fecha_experiencia);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '¡Reserva confirmada exitosamente!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear la reserva.']);
}

$stmt->close();
$conexion->close();
?>
