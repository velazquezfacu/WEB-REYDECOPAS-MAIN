<?php
session_start();
header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión.']);
    exit();
}

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no válido.']);
    exit();
}

// Validar datos
if (!isset($_POST['id_reserva'])) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID de la reserva.']);
    exit();
}

$id_reserva = intval($_POST['id_reserva']);
$id_usuario = $_SESSION['user_id'];

$conexion = new mysqli("localhost", "root", "", "reyescopas");

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit();
}

// Verificar que la reserva pertenezca al usuario y esté confirmada
$sql_check = "SELECT id, fecha_experiencia FROM reservas_experiencias
              WHERE id = ? AND id_usuario = ? AND estado = 'confirmada'";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("ii", $id_reserva, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Reserva no encontrada o ya cancelada.']);
    $stmt->close();
    $conexion->close();
    exit();
}

$reserva = $resultado->fetch_assoc();
$stmt->close();

// Verificar que la fecha de la experiencia sea futura
if (strtotime($reserva['fecha_experiencia']) <= time()) {
    echo json_encode(['success' => false, 'message' => 'No se puede cancelar una reserva de una experiencia pasada.']);
    $conexion->close();
    exit();
}

// Cancelar la reserva
$sql_update = "UPDATE reservas_experiencias SET estado = 'cancelada' WHERE id = ?";
$stmt = $conexion->prepare($sql_update);
$stmt->bind_param("i", $id_reserva);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Reserva cancelada exitosamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva.']);
}

$stmt->close();
$conexion->close();
?>
