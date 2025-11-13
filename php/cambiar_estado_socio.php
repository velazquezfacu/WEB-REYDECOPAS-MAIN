<?php
session_start();
header('Content-Type: application/json');

// Verificar que el usuario sea admin
$conexion_check = new mysqli("localhost", "root", "", "reyescopas");
if ($conexion_check->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    exit();
}

$categoria_usuario = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $conexion_check->prepare("SELECT categoria FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $categoria_usuario = $row['categoria'];
    }
    $stmt->close();
}
$conexion_check->close();

if (!isset($_SESSION['user_id']) || $categoria_usuario !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para realizar esta acción']);
    exit();
}

// Procesar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_socio = isset($_POST['id_socio']) ? intval($_POST['id_socio']) : 0;
    $nuevo_estado = isset($_POST['nuevo_estado']) ? $_POST['nuevo_estado'] : '';

    if ($id_socio <= 0 || !in_array($nuevo_estado, ['Activo', 'Inactivo'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit();
    }

    $conexion = new mysqli("localhost", "root", "", "reyescopas");
    if ($conexion->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit();
    }

    // Actualizar el estado del socio
    $stmt = $conexion->prepare("UPDATE socio SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_socio);

    if ($stmt->execute()) {
        $accion = $nuevo_estado === 'Activo' ? 'activado' : 'dado de baja';
        echo json_encode([
            'success' => true,
            'message' => "Socio {$accion} correctamente",
            'nuevo_estado' => $nuevo_estado
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
    }

    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
