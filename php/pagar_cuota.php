<?php
session_start();
header('Content-Type: application/json');

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión.']);
    exit();
}

// Verificar que se haya enviado el id de la cuota
if (!isset($_POST['id_cuota'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit();
}

$id_cuota = intval($_POST['id_cuota']);
$id_usuario = $_SESSION['user_id'];

$conexion = new mysqli("localhost", "root", "", "reyescopas");

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
    exit();
}

// Obtener información de la cuota y verificar que pertenezca al usuario
$sql_cuota = "SELECT c.*, s.id_usua, s.id as id_socio
              FROM cuotas c
              INNER JOIN socio s ON c.id_socio = s.id
              WHERE c.id = ? AND s.id_usua = ?";
$stmt_cuota = $conexion->prepare($sql_cuota);
$stmt_cuota->bind_param("ii", $id_cuota, $id_usuario);
$stmt_cuota->execute();
$result_cuota = $stmt_cuota->get_result();

if ($result_cuota->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Cuota no encontrada.']);
    $stmt_cuota->close();
    $conexion->close();
    exit();
}

$cuota = $result_cuota->fetch_assoc();
$stmt_cuota->close();

// Verificar que la cuota esté pendiente
if ($cuota['estado'] !== 'pendiente') {
    echo json_encode(['success' => false, 'message' => 'Esta cuota ya fue pagada.']);
    $conexion->close();
    exit();
}

// INICIAR TRANSACCIÓN
$conexion->begin_transaction();

try {
    // 1. Marcar cuota como pagada
    $sql_update_cuota = "UPDATE cuotas SET estado = 'pagada' WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update_cuota);
    $stmt_update->bind_param("i", $id_cuota);
    $stmt_update->execute();
    $stmt_update->close();

    // 2. Registrar el pago
    $metodo_pago = 'transferencia'; // Por defecto, puedes cambiarlo
    $sql_pago = "INSERT INTO pagos (id_socio, id_cuota, monto, metodo_pago, fecha_pago)
                 VALUES (?, ?, ?, ?, NOW())";
    $stmt_pago = $conexion->prepare($sql_pago);
    $stmt_pago->bind_param("iids", $cuota['id_socio'], $id_cuota, $cuota['monto'], $metodo_pago);
    $stmt_pago->execute();
    $stmt_pago->close();

    // 3. Actualizar cuenta corriente (restar el monto del saldo y actualizar estado)
    $sql_update_cuenta = "UPDATE cuenta_cte
                          SET saldo = saldo - ?,
                              ult_fechpag = NOW(),
                              estado = CASE
                                  WHEN (saldo - ?) <= 0 THEN 'Pagado'
                                  ELSE 'Pendiente'
                              END
                          WHERE id_soc = ?";
    $stmt_cuenta = $conexion->prepare($sql_update_cuenta);
    $stmt_cuenta->bind_param("ddi", $cuota['monto'], $cuota['monto'], $cuota['id_socio']);
    $stmt_cuenta->execute();
    $stmt_cuenta->close();

    // Confirmar transacción
    $conexion->commit();

    echo json_encode(['success' => true, 'message' => 'Pago procesado correctamente.']);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conexion->rollback();
    error_log("Error al procesar pago: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al procesar el pago.']);
}

$conexion->close();
?>
