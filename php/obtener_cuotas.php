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
    echo json_encode(['success' => false, 'message' => 'No tienes permisos']);
    exit();
}

// Obtener cuotas del socio
if (isset($_GET['id_socio'])) {
    $id_socio = intval($_GET['id_socio']);

    $conexion = new mysqli("localhost", "root", "", "reyescopas");
    if ($conexion->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit();
    }

    $sql = "SELECT
                c.numero_cuota,
                c.monto,
                DATE_FORMAT(c.fecha_vencimiento, '%d/%m/%Y') as fecha_vencimiento,
                c.estado,
                DATE_FORMAT(p.fecha_pago, '%d/%m/%Y') as fecha_pago,
                p.metodo_pago
            FROM cuotas c
            LEFT JOIN pagos p ON c.id = p.id_cuota
            WHERE c.id_socio = ?
            ORDER BY c.numero_cuota DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_socio);
    $stmt->execute();
    $result = $stmt->get_result();

    $cuotas = [];
    while ($row = $result->fetch_assoc()) {
        $cuotas[] = $row;
    }

    echo json_encode(['success' => true, 'cuotas' => $cuotas]);

    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID de socio no proporcionado']);
}
?>
