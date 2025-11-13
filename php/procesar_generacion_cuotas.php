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
    $fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';
    $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : 'manual';

    if (empty($fecha_vencimiento)) {
        echo json_encode(['success' => false, 'message' => 'La fecha de vencimiento es requerida']);
        exit();
    }

    // Validar formato de fecha
    $fecha = DateTime::createFromFormat('Y-m-d', $fecha_vencimiento);
    if (!$fecha) {
        echo json_encode(['success' => false, 'message' => 'Formato de fecha inválido']);
        exit();
    }

    $conexion = new mysqli("localhost", "root", "", "reyescopas");
    if ($conexion->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit();
    }

    $conexion->begin_transaction();

    try {
        // Obtener todos los socios activos
        $sql_socios = "SELECT s.id, p.costo_plan,
                       (SELECT MAX(numero_cuota) FROM cuotas WHERE id_socio = s.id) as ultima_cuota
                       FROM socio s
                       JOIN planes p ON s.id_plan = p.id
                       WHERE s.estado = 'Activo'";

        $result = $conexion->query($sql_socios);

        if ($result->num_rows === 0) {
            throw new Exception('No hay socios activos para generar cuotas');
        }

        $cuotas_generadas = 0;
        $total_generado = 0;

        // Preparar statement para insertar cuotas
        $stmt_insert = $conexion->prepare("INSERT INTO cuotas (id_socio, numero_cuota, monto, fecha_vencimiento, estado)
                                           VALUES (?, ?, ?, ?, 'pendiente')");

        // Preparar statement para actualizar cuenta_cte
        $stmt_update = $conexion->prepare("UPDATE cuenta_cte SET saldo = saldo + ? WHERE id_soc = ?");

        while ($socio = $result->fetch_assoc()) {
            $id_socio = $socio['id'];
            $monto = $socio['costo_plan'];
            $numero_cuota = ($socio['ultima_cuota'] ?? 0) + 1;

            // Insertar nueva cuota
            $stmt_insert->bind_param("iids", $id_socio, $numero_cuota, $monto, $fecha_vencimiento);

            if (!$stmt_insert->execute()) {
                throw new Exception('Error al insertar cuota para socio ID: ' . $id_socio);
            }

            // Actualizar saldo en cuenta_cte
            $stmt_update->bind_param("di", $monto, $id_socio);

            if (!$stmt_update->execute()) {
                throw new Exception('Error al actualizar cuenta corriente para socio ID: ' . $id_socio);
            }

            // Actualizar estado de cuenta a Pendiente si se agregó deuda
            $conexion->query("UPDATE cuenta_cte SET estado = 'Pendiente' WHERE id_soc = $id_socio AND saldo > 0");

            $cuotas_generadas++;
            $total_generado += $monto;
        }

        $stmt_insert->close();
        $stmt_update->close();

        $conexion->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Cuotas generadas exitosamente',
            'cuotas_generadas' => $cuotas_generadas,
            'total_generado' => $total_generado,
            'fecha_vencimiento' => date('d/m/Y', strtotime($fecha_vencimiento))
        ]);

    } catch (Exception $e) {
        $conexion->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    $conexion->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
