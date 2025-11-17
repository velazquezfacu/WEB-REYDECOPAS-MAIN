<?php
session_start();

// 1. Proteger el script y la conexión
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no autorizado.");
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../sesion.php");
    exit;
}

if (!isset($_POST['id_plan'])) {
    header("Location: cambiar_plan_socio.php?error=noplan");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "reyescopas");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['user_id'];
$id_nuevo_plan = (int)$_POST['id_plan'];

// 2. Obtener el ID del socio
$sql_socio = "SELECT id FROM socio WHERE id_usua = ?";
$stmt_socio = $conexion->prepare($sql_socio);
$stmt_socio->bind_param("i", $id_usuario);
$stmt_socio->execute();
$result_socio = $stmt_socio->get_result();

if ($result_socio->num_rows === 0) {
    die("Error: No se encontró un registro de socio para este usuario.");
}
$socio = $result_socio->fetch_assoc();
$id_socio = $socio['id'];
$stmt_socio->close();

// 3. Iniciar transacción para asegurar consistencia
$conexion->begin_transaction();

try {
    // Actualizar la tabla 'socio'
    $stmt_update_socio = $conexion->prepare("UPDATE socio SET id_plan = ? WHERE id = ?");
    $stmt_update_socio->bind_param("ii", $id_nuevo_plan, $id_socio);
    $stmt_update_socio->execute();
    $stmt_update_socio->close();

    // Actualizar la tabla 'cuenta_cte'
    $stmt_update_cuenta = $conexion->prepare("UPDATE cuenta_cte SET tipo_plan = ? WHERE id_soc = ?");
    $stmt_update_cuenta->bind_param("ii", $id_nuevo_plan, $id_socio);
    $stmt_update_cuenta->execute();
    $stmt_update_cuenta->close();

    // Si todo fue bien, confirmar los cambios
    $conexion->commit();

    // Redirigir al perfil
    header("Location: ../perfil.php?status=plan_changed");
    exit();

} catch (Exception $e) {
    // Si algo falla, revertir todo
    $conexion->rollback();
    error_log("Error al cambiar de plan: " . $e->getMessage());
    header("Location: cambiar_plan_socio.php?error=db");
    exit();
} finally {
    $conexion->close();
}
?>